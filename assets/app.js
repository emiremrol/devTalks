/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');


$(document).ready(function(){
    $("#summernote").summernote(
        {
            height: '250px',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', []]
            ],
            callbacks: {
                // onPaste: function(e) {
                //     var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                //     e.preventDefault();
                //     document.execCommand('insertText', bufferText, false);
                // },
                onInit: function() {
                    var $editor = $(this);
                    $editor.css('font-family', 'Times New Roman');
                }
            }
        }
    );


    $("#summernote2").summernote(
        {
            toolbar: false,
            callbacks: {
                onInit: function() {

                    var $editor = $(this);
                    $editor.css('font-family', 'Times New Roman');
                    // Деактивиране на всички събития
                    $('#summernote2').summernote('disable');
                },
                onPaste: function(e) {
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                    e.preventDefault();
                    document.execCommand('insertText', false, bufferText);
                }
            }
        }
    );
})



setTimeout(hideFlash, 10000);

function hideFlash(){
    let flash = document.querySelector('.flash-notice')
    flash.style.display = 'none';
}


