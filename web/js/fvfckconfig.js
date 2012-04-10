// FCKConfig.Plugins.Add( 'autogrow' ) ;
// FCKConfig.Plugins.Add( 'dragresizetable' );

FCKConfig.SkinPath = FCKConfig.BasePath + 'skins/office2003/' ;

FCKConfig.ToolbarSets["fv"] = [
    ['Source','DocProps','-','Save','NewPage','Preview','-','Templates'],
    ['Cut','Copy','Paste','PasteText','PasteWord','-','Print','SpellCheck'],
    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
    '/',
    ['Bold','Italic','Underline','StrikeThrough','-','Subscript','Superscript'],
    ['OrderedList','UnorderedList','-','Outdent','Indent','Blockquote','CreateDiv'],
    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
    ['Link','Unlink','Anchor'],
    ['Image','Flash','Table','Rule','SpecialChar','PageBreak'],
    '/',
    ['Style','FontFormat','FontName','FontSize'],
    ['TextColor','BGColor'],
    ['FitWindow','ShowBlocks','-','About']      // No comma for the last row.
] ;
