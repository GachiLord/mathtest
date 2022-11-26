import { async } from "regenerator-runtime"
import render from './render'
import $ from 'jquery'

export default async function show(num){
    let content = await $.ajax({
        url: '../../manager/showTests.php',
        method: 'post',
        data: {num: num},
    } );

    render($('.test-view'),JSON.parse(content),'content');


    $('.date').each( function(){
        $(this).html( $(this).html() );
    } );
    $('.Name').each( function(){
        if( $(this).html() != ' ' ){
            $(this).html( $(this).html() );
        }
        else{
             $(this).html( 'Название не указано');
        }
        
    } );
    $('.Creator').each( function(){
        if( $(this).html() != ' ' ){
            $(this).html( $(this).html() );
        }
        else{
            $(this).html( 'Автор не указан');
       }
        
    } );
    $('.Indeficator').each( function(){
        let link = 'http://localhost/launch.htm?' + $(this).html().trim();
        $(this).html(`<a class="openBut" href="${link}">Пройти тест</a>`);
    } );
}