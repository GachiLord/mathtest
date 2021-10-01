import '../assets/css/main.css'
import post from './module/post'
import $ from 'jquery'
import '../assets/css/auth.css'
import { async } from 'regenerator-runtime';
import main from './module/main';


    

    //register
    $( async () => {
        main(); 

        $('.openBut').on('click', async ()=>{
            let response = await post('Auth','register',  JSON.stringify( { login: $('#login').val(), password: $('#password').val(), name:$('#name').val() } ) );
            if ( response.includes('Вы зарегистрированы') ) {
                location.assign('index');
            }
            else{
                $('body').prepend(response);
                setTimeout(() => { 
                    $('.error').animate({opacity:0})
                    $('.massage').animate({opacity:0})
                }, 2000);
            }
        } );
        
    } );



    

