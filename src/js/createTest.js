import Test from './module/Class/Test'
import Editor from './module/Class/Editor'
import $ from 'jquery'
import '../assets/css/test.css'
import escape_text from './module/escapeText'
import '../assets/css/toggle.css'
import '../assets/css/main.css'
import checkThem from './module/checkboxesChecker'
import post from './module/post'
import close from '../assets/img/close.png'
import { async } from 'regenerator-runtime'
import getVal from './module/getVal'
import main from './module/main'
import massage from './module/massage'






const editor = new Editor($('.editor')); 


    $( ()=>{
        //close button
        $('.delete-task').css({'background-image':`url(${close})`});
        //toggle
        $('.show-button').on('click',function(){
            if ( editor.toggle == true ){
                $('.show-button').val('Тест доступен по ссылке');
                editor.toggle = 0;
            }
            else{
                $('.show-button').val('Тест доступен всем');
                editor.toggle = 1;
            }
        });
        //make focus on task
        $('.editblock').on('mousedown', ()=> { return false; });
        $('#name').on('click', function (){ $(this).trigger('focus'); });
        //paste in caret`s position
        $('body').on('paste', '.editor', function(e){
            e.preventDefault();
            let text = (e.originalEvent || e).clipboardData.getData('text/plain');
            document.execCommand('insertHtml', false, escape_text(text));
        });
        //paste html ELement
        $('.pasteButton').on('click', function(){
            editor.paste($(this).attr('what'));
        } );
        //add task
        $('.add-task').on('click', function(){
            editor.addTask();
            $('.delete-task').css({'background-image':`url(${close})`});
            $('.new-task').hide();
            $('.new-task').animate({ opacity: 'show'}, 500);
            $('.new-task').removeClass('new-task');
            

            $('.delete-task').on('click', function(){
                let parent = $(this).parent();
                $(this).parent().animate({ opacity: 'hide' }, 500, function(){
                    editor.deleteTask(parent);
                } );
            });
        });

        //delete task
        $('.delete-task').on('click', function(){
            let parent = $(this).parent();
            $(this).parent().animate({ opacity: 'hide' }, 500, function(){
                editor.deleteTask(parent);
            } );

        } );
        //create test
        $('.push').on('click', function () {
            if ($('#name').val() !== '' ){
                //prepare text
                editor.serialize();
                checkThem($('.editor'));
                //create new Test
                const test = new Test( $('#name').val() );
                //add content in test
                test.answers = editor.getAnswers();
                test.text = editor.getText();
                test.show = editor.toggle;
                //sending test
                editor.create(test);
            }
            else massage('Вы не ввели название теста или не заполнили поля ответов', 'error');
        });
        //edit test
        if ( !isNaN(document.location.pathname.split("/").pop()) ){
            editor.edit();
            $('.push').off('click');
            $('.push').on('click', function(){
                checkThem($('.editor'));
                if ( $('#name').val() !== '' ){
                    editor.serialize();
                    editor.update();      
                }
                else massage('Вы не ввели название теста или не заполнили поля ответов', 'error');                    

            });
        }
    } );

    
$(async () => {
    main();
} );