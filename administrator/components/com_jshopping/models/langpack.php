<?php
defined('_JEXEC') or die();

class JshoppingModelLangpack extends JModelLegacy{

    public function getFileConstants($_folders, $langfolder){
        $langs = array();
        $_langs_path = array();
        $type = 'php';
        $files = JFolder::files($_folders[$langfolder]['fullname'], '\.php$');
        if (count($files)) {
            foreach ($files as $v) {
                $_files[] = substr($v, 0, strpos($v, ".php"));
            }
        }
        if (!count($_files)) {
            $type = 'ini';
            $files = JFolder::files($_folders[$langfolder]['fullname'], '\.ini$');
            if (count($files)) {
                foreach ($files as $v) {
                    $_files[] = substr($v, 0, strpos($v, ".ini"));
                }
            }
        }        
        if (count($_files)) {
            foreach ($_files as $v) {
                if ($v == 'en-GB') {
                    array_unshift($langs, $v);
                    array_unshift($_langs_path, $_folders[$langfolder]['fullname'] . '/' . $v . '.'.$type);
                } else {
                    array_push($langs, $v);
                    array_push($_langs_path, $_folders[$langfolder]['fullname'] . '/' . $v . '.'.$type);
                }
            }
            
            foreach ($_langs_path as $k => $v) {
                if (!file_exists($v)) {
                    exit();
                }
                $_lang = file_get_contents($v);
                if ($k == 0) {
                    if ($type=='php'){
                        $_header = preg_match("/\/\*\*(.*)\*\//si", $_lang, $matches);
                        if ($_header){
                            $header = $matches[0];
                        }
                    }else{
                        $header = '';
                    }
                    if ($type=='php'){
                        $_constants = preg_match_all("/define\(['\"](.*)['\"], */Usi", $_lang, $matches);
                        if ($_constants){
                            $constants = $matches[1];
                        }
                    }else{
                        $_constants = parse_ini_file($v);
                        $constants = array_keys($_constants);                        
                    }
                }
                if ($type=='php'){
                    $_lang_constants = preg_match_all("/define\(['\"](.*)['\"], */Usi", $_lang, $matches);
                    if ($_lang_constants) {
                        $lang_constants = $matches[1];
                        $_transl = preg_match_all("/, *['\"](.*)['\"] *\)/Usi", $_lang, $matches);
                        foreach ($lang_constants as $key => $value) {
                            $translate[$langs[$k]][$value] = $matches[1][$key];
                        }
                    }
                }else{
                    $translate[$langs[$k]] = parse_ini_file($v);
                }
            }
        }
        return array(
            'header'=>$header,
            'constants'=>$constants,
            'translate'=>$translate,
            'langs'=>$langs,
            'type'=>$type
        );
    }

    public function save($langfolder, $constants, $langs, $fileheader, $type='php'){
        $folders = $this->getFiles();
        $files = JFolder::files($folders[$langfolder]['fullname'] . '/', '\.'.$type.'$');
        if (count($files)) {
            foreach ($files as $v) {
                $_files[] = substr($v, 0, strpos($v, ".php"));
            }
        }        
        if (count($langs)) {
            foreach ($langs as $k => $v) {
                if (in_array($k, $_files)) {
                    JFile::copy($folders[$langfolder]['fullname'] . '/' . $k . '.'.$type, $folders[$langfolder]['fullname'] . '/' . $k . '.last.bkp');
                    $_original_file = $folders[$langfolder]['fullname'] . '/' . $k . '.original.bkp';
                    if (!JFile::exists($_original_file)) {
                        JFile::copy($folders[$langfolder]['fullname'] . '/' . $k . '.'.$type, $_original_file);
                    }
                }
                $list_constants = explode("\n", $constants);
                $list_text = explode("\n", $v);

                if ($type=='php'){
                    $file = '<?php' . PHP_EOL . $fileheader . PHP_EOL . 'defined(\'_JEXEC\') or die(\'Restricted access\');' . PHP_EOL . PHP_EOL;
                    foreach ($list_constants as $_k => $constant) {
                        $file .= "define('" . rtrim($list_constants[$_k]) . "', '" . str_replace(array("\\", "'"), array("\\\\", "\'"), rtrim($list_text[$_k])) . "');" . PHP_EOL;
                    }
                    $file .='?>';
                }else{
                    $file = PHP_EOL;
                    foreach ($list_constants as $_k => $constant) {
                        $file .= rtrim($list_constants[$_k]).'='.'"'. str_replace(array('"'), array('\"'), rtrim($list_text[$_k])) . '"' . PHP_EOL;
                    }
                }
                JFile::write($folders[$langfolder]['fullname'] . '/' . $k . '.'.$type, $file);
            }
        }
    }

    public function getFiles() {
        $langpath = JPATH_ROOT . '/components/com_jshopping/lang';
        $adminlangpath = JPATH_ADMINISTRATOR . '/components/com_jshopping/lang';
        $folders = JFolder::listFolderTree($langpath, '.');
        $adminfolders = JFolder::listFolderTree($adminlangpath, '.');
        $_folders = array();
        $temp['name'] = _JSHOP_LANG_PACK_ADMIN;
        $temp['disable'] = true;
        $_folders[] = $temp;
        $temp['fullname'] = $adminlangpath;
        $temp['name'] = '-- ' . _JSHOP_LANG_PACK_MAIN;
        $temp['disable'] = false;
        $_folders[] = $temp;
        foreach ($adminfolders as $v) {
            $v['name'] = '-- ' . $v['name'];
            $_folders[] = $v;
        }
        $temp['name'] = _JSHOP_LANG_PACK_SITE;
        $temp['disable'] = true;
        $_folders[] = $temp;
        $temp['fullname'] = $langpath;
        $temp['name'] = '-- ' . _JSHOP_LANG_PACK_MAIN;
        $temp['disable'] = false;
        $_folders[] = $temp;
        foreach ($folders as $v) {
            $v['name'] = '-- ' . $v['name'];
            $_folders[] = $v;
        }
        foreach ($_folders as $k => &$v) {
            $v['key'] = $k;
        }
        return $_folders;
    }
    
 
}