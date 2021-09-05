import '../assets/css/main.css'
import post from './module/post'
import $ from 'jquery'
import '../assets/css/auth.css'
import { async } from 'regenerator-runtime';
import main from './module/main';






    //login
    $( async () => {
        
           $('.openBut').on('click', async ()=>{
                let response = await post('Auth','login', JSON.stringify( { login: $('#login').val(), password: $('#password').val() } ) );
        
                if ( response.includes('Добро') ){location.assign('index');} 
                else {
                $('body').prepend(response);
                    setTimeout(() => { 
                    $('.error').animate({opacity:0})
                    $('.massage').animate({opacity:0})
                }, 2000);
            };
    } );
 
    main();
    } )

