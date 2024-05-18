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
            tabsize:2,
            width:750,
            height:300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', []]
            ]
        }
    );


    $("#summernote2").summernote(
        {
            toolbar: false,
            tabsize: 2,
            width: 750,
            callbacks: {
                onInit: function() {
                    // Деактивиране на всички събития
                    $('#summernote2').summernote('disable');
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

