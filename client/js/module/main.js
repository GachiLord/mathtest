import { async } from "regenerator-runtime";
import $ from 'jquery'
import post from './post'
import baseurl from "./baseurl";
import "../../assets/css/bootstrap.min.css";
import bootstrap from 'bootstrap';
import '../../assets/css/main.css';
import massage from "./massage";



export default async function (){
    
    
    $( () => {
        //buttonEventListener
        $('[controller]').on('click', async function () {
            if ($(this).attr('reactable') != 'false'){
                if ( $(this).attr('reload') == 'true' ) location.reload();
                massage(await post( $(this).attr('controller'), $(this).attr('action'),  $(this).attr('parameters') ));
            }
        });
        //navbar`s link choosing
        $('.nav-link').each( function(){
            if ( $(this).html().toLocaleLowerCase() === $('title').html().toLocaleLowerCase() ) $(this).addClass('active');
        } );

    } );

}