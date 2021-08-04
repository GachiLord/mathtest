import { async } from "regenerator-runtime";
import $ from 'jquery'
import post from './post'

export default async function (){
    //menu
    $('header').append( await post('Info','menu') );
    //quit
    $('.quit').on('click', ()=>{
        location.assign('http://mathtest');
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