import { async } from "regenerator-runtime";
import $ from 'jquery'
import post from './post'
import baseurl from "./baseurl";

export default async function (){
    //menu
    $('header').append( await post('Info','menu') );
    //buttonEventListener
    $( () => {
    $('[controller]').on('click', async function () {
        if ( $(this).attr('class') === 'quit' ) location.assign(baseurl)
        else location.reload();
            $('body').prepend( await post( $(this).attr('controller'), $(this).attr('action'),  $(this).attr('parameters') ) );
            setTimeout(() => {
                    $('.error').animate({opacity:0})
                    $('.massage').animate({opacity:0})
            }, 2000); 
    });  
    } );
  
}