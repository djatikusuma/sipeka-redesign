<?php

// check waktu
if (!function_exists('check_time')) {
    function check_time($date = NULL, $duration = 60)
    {
        // date_default_timezone_set('Asia/Jakarta');
        $output = [];
        $now = date("Y-m-d H:i:s");
        if (strtotime($date) < strtotime($now)) {
            if (strtotime("+$duration minutes", strtotime($date)) > strtotime($now)) {
                $output['status'] = "Sedang Berlangsung";
                $output['type'] = 'info';
                $output['open_input'] = true;
                $output['open_preview'] = true;
            } else {
                $output['status'] = "Selesai";
                $output['type'] = 'success';
                $output['open_input'] = false;
                $output['open_preview'] = true;
            }
        } else if (strtotime($date) > strtotime($now)) {
            $output['status'] = "Belum Mulai";
            $output['type'] = 'danger';
            $output['open_input'] = false;
            $output['open_preview'] = false;
        } else {
            if (strtotime("+$duration minutes", strtotime($date)) > strtotime($now)) {
                $output['status'] = "Sedang Berlangsung";
                $output['type'] = 'info';
                $output['open_input'] = true;
                $output['open_preview'] = true;
            } else {
                $output['status'] = "Selesai";
                $output['type'] = 'success';
                $output['open_input'] = false;
                $output['open_preview'] = true;
            }
        }

        return $output;
    }
}

if (!function_exists('create_form')) {
    function create_form($form = NULL)
    {

        // $mandatory = $form['mandatory'] === 'required' ? "*" : "";

        if (in_array($form['type'], ['text', 'password', 'email', 'number'])) {
            return '
                        <div class="form-group pb-2">
                                <label for="' . $form['id'] . '" class="'.$form['mandatory'].'">' . $form['label'] . ' ' . $mandatory . '</label>
                                <input type="text" name="' . $form['id'] . '" id="' . $form['id'] . '" class="form-control" ' . $form['mandatory'] . '>
                        </div>
                ';
        } else if ($form['type'] == 'radio') {
            $output = '<label for="' . $form['id'] . '">' . $form['label'] . '</label>';

            // $source = $CI->sourcemodel->getAll(true, ['field_name' => $form['source']]);
            // $field = json_decode($source->field_value, true);

            // foreach ($field as $option) {
            //     $output .= '
            //                     <div class="form-check">
            //                             <input class="form-check-input" type="radio" name="' . $form['id'] . '" id="' . $option . '" value="' . $option . '">
            //                             <label class="form-check-label" for="' . $option . '">
            //                                     ' . $option . '
            //                             </label>
            //                     </div>
            //             ';
            // }

            return $output;
        }
    }
}
