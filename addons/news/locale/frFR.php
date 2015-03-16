<?php
/**


// -[ frFR Localization ]-

// Installer names
$lang['news']         = 'Articles';
$lang['news_desc']    = 'Syst�me de gestion d\'articles avec commentaires';

// Button names
$lang['cms_button']   = 'Guild Portal|Post articles and other items of interest';

$lang['no_news']      = 'Pas d\'article disponible';
$lang['bad_news_id']  = 'Mauvais identifiant d\'article';

$lang['add_news']     = 'Ajouter un article';
$lang['enable_html']  = 'Activer HTML';
$lang['disable_html'] = 'D�sactiver HTML';
$lang['posted_by']    = '�crit par';
$lang['edit']         = '�diter';
$lang['edit_news']    = '�diter l\'article';
$lang['comments']     = 'Commentaires';
$lang['add_comment']  = 'Ajouter un commentaire';
$lang['edit_comment'] = '�diter un commentaire';
$lang['n_comment']    = '%s commentaire';
$lang['n_comments']   = '%s commentaires';

$lang['whos_online']  = 'Who\'s Online';
$lang['total']			= 'Total';
$lang['reg']			= 'Registered';
$lang['guests']			= 'Guests';
$lang['bots']			= 'Bots';

$lang['b_title']  = 'Title';
$lang['b_desc']   = 'Desc';
$lang['b_url']    = 'Link';
$lang['b_image']  = 'Image File';
$lang['b_add']    = 'Add Slider Image';
$lang['b_upload'] = 'Upload a Slider Image';
$lang['b_video'] = 'Video';

$lang['news_edit_success']     = 'Article �dit� avec succ�s';
$lang['news_add_success']      = 'Article ajout� avec succ�s';
$lang['slider_add_success']    = 'Slider image %1$s added successfully';
$lang['slider_error_db']       = 'There was a Database error while adding the slider image';
$lang['slider_file_error']     = 'Your upload was not completed. Image [%1$s] could not be uploaded.';
$lang['news_error_process']    = 'Il y a eu un probl�me lors du traitement de l\'article';

$lang['comment_edit_success']  = 'Commentaire �dit� avec succ�s';
$lang['comment_add_success']   = 'Commentaire ajout� avec succ�s';
$lang['comment_error_process'] = 'Il y a eu un probl�me lors du traitement de votre commentaire';

// Config strings
$lang['admin']['cmsmain_conf']          = 'Configuration des articles|Configuration de base pour les articles';
$lang['admin']['cmsmain_slider']        = 'Slider|Image Slider configuration';
$lang['admin']['cmsmain_slider_images'] = 'Slider Images';
$lang['admin']['cmsmain_slider_add']    = 'Add Images';
$lang['admin']['news_add']              = 'Ajouter des articles|Accr�ditation minimale pour pouvoir ajouter un article.';
$lang['admin']['news_edit']             = '�diter des articles|Accr�ditation minimale pour pouvoir �diter un article.';
$lang['admin']['comm_add']              = 'Ajouter des commentaires|Accr�ditation minimale pour pouvoir ajouter un commentaire.';
$lang['admin']['comm_edit']             = '�diter des commentaires|Accr�ditation minimale pour pouvoir �diter un commentaire.';
$lang['admin']['news_html']             = 'HTML dans l\'article|Pour autoriser ou interdire par d�faut l\'usage du HTML dans les articles.<br />N\'affecte pas les articles d�j� existants.';
$lang['admin']['comm_html']             = 'HTML dans le commentaire|Pour autoriser ou interdire par d�faut l\'usage du HTML dans les commentaires.<br />N\'affecte pas les commentaires d�j� existants.';
$lang['admin']['news_nicedit']          = 'Nicedit text Box|Whether to enable or disable the Nicedit text box.';

// Slider options
$lang['admin']['slider_skin']              = 'Slider Skin|Select the style of the buttons and icons for the slider';
$lang['admin']['slider_alignment']         = 'Alignment|topLeft, topCenter, topRight, centerLeft, center, centerRight, bottomLeft, bottomCenter, bottomRight';
$lang['admin']['slider_autoAdvance']       = 'Auto Advance|Sets the slider to auto-advance';
$lang['admin']['slider_mobileAutoAdvance'] = 'Mobile Auto Advance|Auto-advancing for mobile devices';
$lang['admin']['slider_barDirection']      = 'Bar Direction|';
$lang['admin']['slider_barPosition']       = 'Bar Position|';
$lang['admin']['slider_easing']            = 'Easing|';
$lang['admin']['slider_mobileEasing']      = 'Mobile Easing|Leave empty if you want to display the same easing on mobile devices and on desktop etc.';
$lang['admin']['slider_fx']                = 'Effect|Choose the type of sliding effect<br />- You can select more than one effect';
$lang['admin']['slider_mobileFx']          = 'Mobile Effect|Leave empty if you want to display the same effect on mobile devices and on desktop etc.';
$lang['admin']['slider_gridDifference']    = 'Grid Difference|To make the grid blocks slower than the slices, this value must be smaller than transPeriod';
$lang['admin']['slider_height']            = 'Height|Height of the slider images.<br />- You can type pixels (for instance \'300px\'), a percentage (relative to the width of the slideshow, for instance \'50%\') or auto';
$lang['admin']['slider_hover']             = 'Hover|Pause on mouse hover. Not available for mobile devices';
$lang['admin']['slider_loader']            = 'Loader Type|Loader display type.<br />- Even if you choose "pie", old browsers like IE8- can\'t display it... they will display always a loading bar';
$lang['admin']['slider_loaderColor']       = 'Loader Color|Color of the loader image';
$lang['admin']['slider_loaderBgColor']     = 'Loader Background Color|Background color of the loader image';
$lang['admin']['slider_loaderOpacity']     = 'Loader Opacity|Transparentcy of the loader image';
$lang['admin']['slider_loaderPadding']     = 'Loader Padding|How many empty pixels you want to display between the loader and its background';
$lang['admin']['slider_loaderStroke']      = 'Loader Stroke|The thickness both of the pie loader and of the bar loader.<br />Remember: for the pie, the loader thickness must be less than a half of the pie diameter';
$lang['admin']['slider_minHeight']         = 'Min Height|Minimum hieght for the slider<br />- You can also leave it blank';
$lang['admin']['slider_navigation']        = 'Navigation|Display the navigation buttons (prev, next and play/stop buttons)';
$lang['admin']['slider_navigationHover']   = 'Navigation Hover|If on, the navigation buttons (prev, next and play/stop buttons) will be visible on hover state only, if off they will be visible always';
$lang['admin']['slider_mobileNavHover']    = 'Mobile Navigation Hover|Same as above, but for mobile devices';
$lang['admin']['slider_opacityOnGrid']     = 'Opacity On Grid|Applies a fade effect to blocks and slices<br />- If your slideshow is fullscreen or simply big, it\'s recommended to set it false to have a smoother effect';
$lang['admin']['slider_overlayer']         = 'Overlayer|Places a layer over the images to prevent the users from saving them simply by clicking the right button of their mouse';
$lang['admin']['slider_pagination']        = 'Pagination|Show the pagination icons';
$lang['admin']['slider_playPause']         = 'Play/Pause|Display the play/pause buttons';
$lang['admin']['slider_pauseOnClick']      = 'Pause On Click|Stops the slideshow when you click the sliders';
$lang['admin']['slider_pieDiameter']       = 'Pie Diameter|Diameter of the pie loader';
$lang['admin']['slider_piePosition']       = 'Pie Position|Where the pie loader should be placed';
$lang['admin']['slider_portrait']          = 'Portrait|Enable if you don\'t want your images to be cropped';
$lang['admin']['slider_cols']              = 'Columns|';
$lang['admin']['slider_rows']              = 'Rows|';
$lang['admin']['slider_slicedCols']        = 'Sliced Columns|If 0 the same value of cols';
$lang['admin']['slider_slicedRows']        = 'Sliced Rows|If 0 the same value of rows';
$lang['admin']['slider_slideOn']           = 'Slide On|When the transition effect will be applied';
$lang['admin']['slider_thumbnails']        = 'Thumbnails|Display thumbnails when you hover the pagination icons';
$lang['admin']['slider_time']              = 'Time|milliseconds between the end of the sliding effect and the start of the next one';
$lang['admin']['slider_transPeriod']       = 'Transition Period|Length of the sliding effect in milliseconds';
