import $ from 'jquery';
import { async } from 'regenerator-runtime';
import main from './module/main';
import massage from './module/massage';
import post from './module/post';


$( ()=> {
    main();
    $('#save').on('click', async function(){
    massage( await post('Profile', 'edit', JSON.stringify( { login: $('#save').attr('login'), name: $('#name').val(), password: $('#newPassword').val(), OldPassword: $('#password').val(), role: $('#role').val()} ) ) );
    });
    $('#change').on('click', async function(){
        massage( await post('Profile', 'ChangeRole', JSON.stringify( { role: $('option:selected').val(), login: $('#change').attr('login') } )) );
    });
} );