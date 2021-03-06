<?php
/**
 * Option Processor

 * @package NRS
 * @uses NRSDepositManager, NRSDiscountManager, NRSPrepaymentManager
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Option;
use NativeRentalSystem\Models\AbstractElement;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iElement;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class ItemOption extends AbstractElement implements iElement
{
    protected $conf 	    = NULL;
    protected $lang 		= NULL;
    protected $debugMode 	= 0;
    protected $optionId		= 0;

    /**
     * ItemOption constructor.
     * @param ExtensionConfiguration $paramConf
     * @param Language $paramLang
     * @param array $paramSettings
     * @param $paramOptionId
     */
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramOptionId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;

        $this->optionId = StaticValidator::getValidValue($paramOptionId, 'positive_integer', 0);
    }

    /**
     * @param $paramOptionId
     * @return mixed
     */
    private function getDataFromDatabaseById($paramOptionId)
    {
        $validOptionId = StaticValidator::getValidPositiveInteger($paramOptionId, 0);
        $row = $this->conf->getInternalWPDB()->get_row("
            SELECT option_id, item_id, option_name
            FROM {$this->conf->getPrefix()}options
            WHERE option_id='{$validOptionId}'
        ", ARRAY_A);

        return $row;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getId()
    {
        return $this->optionId;
    }

    /**
     * Element-specific function
     * @return int
     */
    public function getItemId()
    {
        $retItemId = 0;
        $optionData = $this->getDataFromDatabaseById($this->optionId);
        if(!is_null($optionData))
        {
            $retItemId = $optionData['item_id'];
        }

        return $retItemId;
    }

    /**
     * Checks if current user can edit the element
     * @param $paramPartnerId - partner id is mandatory here, as it comes from other plugin
     * @return bool
     */
    public function canEdit($paramPartnerId)
    {
        $canEdit = FALSE;
        if($this->optionId > 0)
        {
            if(current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_items'))
            {
                $canEdit = TRUE;
            } else if($paramPartnerId > 0 && $paramPartnerId == get_current_user_id() && current_user_can('manage_'.$this->conf->getExtensionPrefix().'own_items'))
            {
                $canEdit = TRUE;
            }
        }

        return $canEdit;
    }

    /**
     * @param bool $paramIncludeUnclassified - not used
     * @return mixed
     */
    public function getDetails($paramIncludeUnclassified = FALSE)
    {
        $ret = $this->getDataFromDatabaseById($this->optionId);

        if(!is_null($ret))
        {
            // Make raw
            $ret['option_name'] = stripslashes($ret['option_name']);

            // Add translation
            $ret['translated_option_name'] = $this->lang->getTranslated("io{$ret['option_id']}_option_name", $ret['option_name']);

            // Make output ready for print
            $ret['print_option_name'] = esc_html($ret['option_name']);
            $ret['print_translated_option_name'] = esc_html($ret['translated_option_name']);

            // Prepare output for edit
            $ret['edit_option_name'] = esc_attr($ret['option_name']); // for input field
        }

        return $ret;
    }

    /**
     * @return bool|false|int
     */
    public function save()
    {
        $saved = FALSE;
        $ok = TRUE;
        $validOptionId = StaticValidator::getValidPositiveInteger($this->optionId, 0);
        $validItemId = isset($_POST['item_id']) ? StaticValidator::getValidPositiveInteger($_POST['item_id']) : 0;
        $sanitizedOptionName = isset($_POST['option_name']) ? sanitize_text_field($_POST['option_name']) : '';
        $validOptionName = esc_sql($sanitizedOptionName); // for sql queries only

        $nameExistQuery = "
            SELECT option_id
            FROM {$this->conf->getPrefix()}options
            WHERE option_name='{$validOptionName}'
            AND item_id='{$validItemId}' AND option_id!='{$validOptionId}' AND blog_id='{$this->conf->getBlogId()}'
        ";
        $nameExist = $this->conf->getInternalWPDB()->get_row($nameExistQuery, ARRAY_A);

        if($validItemId == 0)
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_ITEM_OPTION_PLEASE_SELECT_ERROR_TEXT');
        }
        if(!is_null($nameExist))
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_ITEM_OPTION_NAME_EXISTS_ERROR_TEXT');
        }

        if($validOptionId > 0 && $ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
                UPDATE {$this->conf->getPrefix()}options SET
                item_id='{$validItemId}',
                option_name='{$validOptionName}'
                WHERE option_id='{$validOptionId}' AND blog_id='{$this->conf->getBlogId()}'
            ");

            if($saved === FALSE)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_ITEM_OPTION_UPDATE_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_ITEM_OPTION_UPDATED_TEXT');
            }
        } else if($ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
                INSERT INTO {$this->conf->getPrefix()}options
                (
                    item_id, extra_id, option_name, blog_id
                ) VALUES
                (
                    '{$validItemId}', '0', '{$validOptionName}', '{$this->conf->getBlogId()}'
                )
            ");

            if($saved === FALSE || $saved === 0)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_ITEM_OPTION_INSERT_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_ITEM_OPTION_INSERTED_TEXT');

                // Get newly inserted option id
                $validInsertedNewOptionId = $this->conf->getInternalWPDB()->insert_id;

                // Update the core option id for future use
                $this->optionId = $validInsertedNewOptionId;
            }
        }

        return $saved;
    }

    public function registerForTranslation()
    {
        $optionDetails = $this->getDetails();
        if(!is_null($optionDetails))
        {
            $this->lang->register("io{$this->optionId}_option_name", $optionDetails['option_name']);
            $this->okayMessages[] = $this->lang->getText('NRS_ITEM_OPTION_REGISTERED_TEXT');
        }
    }

    public function delete()
    {
        $validOptionId = StaticValidator::getValidPositiveInteger($this->optionId);
        $deleted = $this->conf->getInternalWPDB()->query("
            DELETE FROM {$this->conf->getPrefix()}options
            WHERE option_id='{$validOptionId}' AND blog_id='{$this->conf->getBlogId()}'
        ");

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_ITEM_OPTION_DELETE_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_ITEM_OPTION_DELETED_TEXT');
        }

        return $deleted;
    }
}