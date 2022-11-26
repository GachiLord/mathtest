import LaunchedTest from './module/Class/LaunchTest'
import $ from 'jquery'
import '../assets/css/test.css'
import '../assets/css/main.css'
import "regenerator-runtime/runtime"
import '../assets/css/persons.css'
import post from './module/post'
import main from './module/main'
import { async } from 'regenerator-runtime/runtime'
import baseurl from './module/baseurl'










$( async () => {
    //get test from server
    let test;
    let Lchtest;
    try{
        test = JSON.parse( await post('Test', 'get', JSON.stringify( { publicid: document.location.pathname.split("/").pop() } ) ) );
    }
    catch(e){
        location.href = baseurl + '/404'
    }
    if ( test.length <= 1 ) switch(test[0]){
        case 'late':
            $('.launchTest').append(`<div class="finish-block bg-primary">Время вышло<div>`);
            break;
        case 'NotAuthed':
            $('.launchTest').append(`<div class="finish-block bg-primary">Тесты на время могут проходить только авторизованные пользователи<div>`);
            break;
    }
    else {
        Lchtest = new LaunchedTest( test.name, test.OwnerInfo.name, JSON.parse(test.text), test.date, test.type, test.time );
        //give testname to title
        $('title').html(Lchtest.name);  
        //launch test
        Lchtest.render();
        Lchtest.timer();
    }





} ); 

$( () => {
    main();
});


