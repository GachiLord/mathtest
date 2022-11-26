import post from './module/post'
import $, { event } from 'jquery'
import { async } from 'regenerator-runtime';
import main from './module/main';
import message from './module/massage';





    //login
    $( async () => {
        main();
           $('#push').on('click', async ()=>{
                let response = await post('Auth','login', JSON.stringify( { login: $('#login').val(), password: $('#password').val() } ) );
                
                if ( response.includes('true') ){location.assign('index');} 
                else {
                    message('Неверный логин или пароль', false);
                };
    } );
    } )

