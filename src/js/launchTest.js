import LaunchedTest from './module/Class/LaunchTest'
import $ from 'jquery'
import '../assets/css/test.css'
import '../assets/css/main.css'
import "regenerator-runtime/runtime"
import Test from'./module/Class/Test'
import clearConsole from './module/clearConsole'
import '../assets/css/persons.css'
import post from './module/post'
import checkThem from './module/checkboxesChecker'
import main from './module/main'
import { async } from 'regenerator-runtime/runtime'









$( async () => {
    //get test from server
    let test;
    try{
        test = JSON.parse( await post('Test', 'get', JSON.stringify( { publicid: document.location.pathname.split("/").pop() } ) ) );
    }
    catch(e){
        location.href = location.href.replace(`launch/${document.location.pathname.split("/").pop()}`, 'show?page=20');
    }
    const Lchtest = new LaunchedTest( test.name, test.OwnerInfo.name, JSON.parse(test.text), test.date, test.type, test.time );

    //give testname to title
    $('title').append(Lchtest.name);  
    //launch test
    Lchtest.render();


} ); 

$( () => {
    main();
});




