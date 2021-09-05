import { async } from "regenerator-runtime";
import $ from 'jquery'
import post from './post'
import baseurl from "./baseurl";

export default async function (){
    //menu
    $('header').append( await post('Info','menu') );
    //quit
    $('.quit').on('click', ()=>{
        location.assign(baseurl);
    });
    //buttonEventListener
    $('[controller]').on('click', async function () {
            $('body').prepend( await post( $(this).attr('controller'), $(this).attr('action'),  $(this).attr('parameters') ) );
            setTimeout(() => {
                    $('.error').animate({opacity:0})
                    $('.massage').animate({opacity:0})
            }, 2000); 
            location.reload();
    });    
}