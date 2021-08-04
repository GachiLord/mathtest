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








//get test from server
const test = JSON.parse( await post('LaunchedTest', 'getTest', JSON.stringify( { id: document.location.pathname.split("/").pop() } ) ) );
const Lchtest = new LaunchedTest( test.name, test.creator, test.text, test.date, test.type );



$( ()=> {
    //give testname to title
    $('title').append(Lchtest.name);  
    //launch test
    Lchtest.render();


} ); 



$(async () => {
    main();
});


