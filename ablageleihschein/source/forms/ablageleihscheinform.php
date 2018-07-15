<?php
//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");
class ablageleihscheinform extends moodleform {
    /**
     * Add elements for creating/editing a file profile field.
     * @param moodleform $form
     */
    public function definition() {
        global $CFG;
        $form = $this->_form;
        // Default data.
        /*$form->addElement('hidden', 'defaultdata', '');
        $form->setType('defaultdata', PARAM_TEXT);
        // Param 1 for file type is the maxfiles of the field.
        // Let's prepare the maxfiles popup.
        $options = array();
        //Es kann immer nur eine Datei hochgeladen 
        for ($i = 1; $i <= 1; $i++) {
            $options[$i] = $i;
        }
        $form->addElement('select', 'param1', get_string('maximumupload'), $options);
        $form->setDefault('param1', $CFG->maxbytes);
        $form->setType('param1', PARAM_INT);
        // Param 2 for file type is the maxbytes of the field.
        // Let's prepare the maxbytes popup.
        $choices = get_max_upload_sizes($CFG->maxbytes);
        $form->addElement('select', 'param2', get_string('maximumupload'), $choices);
        $form->setDefault('param2', $CFG->maxbytes);
        $form->setType('param2', PARAM_INT); 



        public function edit_field_add($mform) { */
        $form->addElement('filemanager', $this->inputname, format_string($this->field->name), null, $this->get_filemanageroptions());
    }

    /**
     * Overwrite the base class to display the data for this field
     */
    public function display_data() {
        global $CFG;
        // Default formatting.
        $data = parent::display_data();

        $context = context_user::instance($this->userid, MUST_EXIST);
        $fs = get_file_storage();

        $dir = $fs->get_area_tree($context->id, 'profilefield_file', "files_{$this->fieldid}", 0);
        $files = $fs->get_area_files($context->id, 'profilefield_file', "files_{$this->fieldid}",
                                     0,
                                     'timemodified',
                                     false);

        $data = array();

        foreach ($files as $file) {
            $path = '/' . $context->id . '/profilefield_file/files_' . $this->fieldid . '/' .
                    $file->get_itemid() .
                    $file->get_filepath() .
                    $file->get_filename();
            $url = file_encode_url("$CFG->wwwroot/pluginfile.php", $path, true);
            $filename = $file->get_filename();
            $data[] = html_writer::link($url, $filename);
        }

        $data = implode('<br />', $data);

        return $data;
    }

    /**
     * Saves the data coming from form
     * @param stdClass $usernew data coming from the form
     * @return mixed returns data id if success of db insert/update, false on fail, 0 if not permitted
     */
    public function edit_save_data($usernew) {
        if (!isset($usernew->{$this->inputname})) {
            // Field not present in form, probably locked and invisible - skip it.
            return;
        }

        $usercontext = context_user::instance($this->userid, MUST_EXIST);
        file_save_draft_area_files($usernew->{$this->inputname}, $usercontext->id, 'profilefield_file', "files_{$this->fieldid}", 0, $this->get_filemanageroptions());
        parent::edit_save_data($usernew);
    }

    /**
     * Sets the default data for the field in the form object
     * @param  moodleform $mform instance of the moodleform class
     */
    public function edit_field_set_default($form) {
        if ($this->userid && ($this->userid !== -1)) {
            $filemanagercontext = context_user::instance($this->userid);
        } else {
            $filemanagercontext = context_system::instance();
        }

        $draftitemid = file_get_submitted_draft_itemid($this->inputname);
        file_prepare_draft_area($draftitemid, $filemanagercontext->id, 'profilefield_file', "files_{$this->fieldid}", 0, $this->get_filemanageroptions());
        $form->setDefault($this->inputname, $draftitemid);
        $this->data = $draftitemid;

        parent::edit_field_set_default($form);
    }

    /**
     * Just remove the field element if locked.
     * @param moodleform $mform instance of the moodleform class
     * @todo improve this
     */
    public function edit_field_set_locked($form) {
        if (!$form->elementExists($this->inputname)) {
            return;
        }
        if ($this->is_locked() and !has_capability('moodle/user:update', context_system::instance())) {
            $form->removeElement($this->inputname);
        }
    }

    /**
     * Hook for child classess to process the data before it gets saved in database
     * @param stdClass $data
     * @param stdClass $datarecord The object that will be used to save the record
     * @return  mixed
     */
    public function edit_save_data_preprocess($data, $datarecord) {
        return 0;  // we set it to zero because this value is actually redaundant
                    // it cannot be set to null or an empty string either because the field's
                    // value will not be shown on user's profile.
    }

    /**
     * Loads a user object with data for this field ready for the edit profile
     * form
     * @param stdClass $user a user object
     */
    public function edit_load_user_data($user) {
        $user->{$this->inputname} = null;   // it should be set to null, otherwise the loaded files will
                                            // get manipulated when $userform->set_data($user) is called
                                            // later in user/edit.php or user/editadvanced.php
    }

    private function get_filemanageroptions() {
        return array(
            'maxfiles' => $this->field->param1,
            'maxbytes' => $this->field->param2,
            'subdirs' => 0,
            'accepted_types' => '*'
        );
    }
}
    
  

