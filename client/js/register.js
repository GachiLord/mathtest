import post from './module/post'
import $ from 'jquery'
import { async } from 'regenerator-runtime';
import main from './module/main';
import message from './module/massage';

    

    //register
    $( async () => {
        main(); 

        $('#push').on('click', async ()=>{
            let response = await post('Auth','register',  JSON.stringify( { login: $('#login').val(), password: $('#password').val(), name:$('#name').val() } ) );
            if ( response.includes('Вы зарегистрированы') ) {
                location.assign('index');
            }
            else{
                message(response);
            }
        } );
        
    } );



    

