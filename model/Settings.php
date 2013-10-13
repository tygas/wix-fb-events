<?php

class Settings {
    static function get($sIntance = 'instance'){

        $aJsonSettings = json_decode(
            file_get_contents(ROOT.'/settings/'.$sIntance .'.json')
        );
lg($aJsonSettings);
        return $aJsonSettings;
    }
}
