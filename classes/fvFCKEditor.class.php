<?php

require_once (dirname(__FILE__) . "/fckeditor/fckeditor.php");

class fvFCKEditor extends FCKEditor {
    public $Class, $Style, $HtmlMame;
    
    public function __construct($name) {
        
        $this->HtmlMame = $name;
        
        $name = str_replace(array("[", "]"), array("_", ""), $name);
        
        parent::__construct($name);
        
        $this->BasePath = fvSite::$fvConfig->get("editor.basepath");
        $this->Config["CustomConfigurationsPath"] = fvSite::$fvConfig->get("editor.config");
        $this->ToolbarSet = fvSite::$fvConfig->get("editor.toolbar");
        $this->Class = fvSite::$fvConfig->get("editor.className");
        $this->Style = fvSite::$fvConfig->get("editor.style");
        $this->Width = "95%";
    }
    
    public function __toString() {
        return $this->CreateHtml();
    }
    
    public function CreateHtml($value = null)
    {
        if ($value) $this->Value = $value;
        
        $HtmlValue = htmlspecialchars( $this->Value ) ;

        $Html = '' ;

        if ( $this->IsCompatible() )
        {
            if ( isset( $_GET['fcksource'] ) && $_GET['fcksource'] == "true" )
                $File = 'fckeditor.original.html' ;
            else
                $File = 'fckeditor.html' ;

            $Link = "{$this->BasePath}editor/{$File}?InstanceName={$this->InstanceName}" ;

            if ( $this->ToolbarSet != '' )
                $Link .= "&amp;Toolbar={$this->ToolbarSet}" ;

            // Render the linked hidden field.
            $Html .= "<input type=\"hidden\" id=\"{$this->InstanceName}\" name=\"{$this->HtmlMame}\" value=\"{$HtmlValue}\" style=\"display:none\" class=\"_fcke{$this->InstanceName}\" />" ;

            // Render the configurations hidden field.
            $Html .= "<input type=\"hidden\" id=\"{$this->InstanceName}___Config\" value=\"" . $this->GetConfigFieldString() . "\" style=\"display:none\" />" ;

            // Render the editor IFRAME.
            $Html .= "<iframe id=\"{$this->InstanceName}___Frame\" src=\"{$Link}\" width=\"{$this->Width}\" height=\"{$this->Height}\" class=\"{$this->Class}\" style=\"{$this->Style   }\" frameborder=\"0\" scrolling=\"no\"></iframe>" ;
            
            $Html .= "

            ";
        }
        else
        {
            if ( strpos( $this->Width, '%' ) === false )
                $WidthCSS = $this->Width . 'px' ;
            else
                $WidthCSS = $this->Width ;

            if ( strpos( $this->Height, '%' ) === false )
                $HeightCSS = $this->Height . 'px' ;
            else
                $HeightCSS = $this->Height ;

            $Html .= "<textarea name=\"{$this->InstanceName}\" rows=\"4\" cols=\"40\" style=\"width: {$WidthCSS}; height: {$HeightCSS}\">{$HtmlValue}</textarea>" ;
        }

        return $Html ;
    }
}