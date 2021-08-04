import $ from 'jquery'
import { async } from 'regenerator-runtime';
import render from './render'

export default async function(){

    let Persons = await $.ajax({
            url: '../../manager/showpersons.php',
            method: 'post',
            data: {param: 'indeficator =' + location.search.slice(1) + ' ORDER BY `persons`.`result` DESC'},
        });

    render($('.finish'),JSON.parse(Persons),'person');

    $('.name').each( function(){
        if( $(this).html() != ' ' ){
            $(this).html( $(this).html() );
        }
        else{
             $(this).html( 'Пользователь');
        }
        
    } );
    $('.result').each( function(){
            $(this).html( 'Балл:' + $(this).html() );
    });


    if ( Persons === 'null' ){
        $('.finish').append('<div style="font-size:48px;margin:auto;">Здесь как-то пусто</div>');
    }
}