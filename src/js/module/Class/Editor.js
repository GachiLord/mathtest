import $ from 'jquery'
import task from '../pastetask'
import getVal from '../getVal'
import post from '../post';
import { async } from 'regenerator-runtime';
import close from '../../../assets/img/close.png'
import checkThem from '../checkboxesChecker'
import baseurl from '../baseurl';


export default class Editor {
    constructor(){
        this.text = [];
        this.toggle = true;
        this.updateId = null;
    }

    paste(what){
        let block = task(what);
        
        document.execCommand('insertHTML', false, block);
        this.serialize();              
        checkThem($('.editor'));
    }

    addTask(){
        $('.editor').append('<div class="task-wrap new-task"><div class="task" Contenteditable="true"></div><div class="delete-task"></div></div>');
    }

    deleteTask(task){
        $(task).detach();
    }

    async create(test){
        //some ajax
        $('.question').each( function(){ getVal($(this), ''); } );
        location.replace( 'edit/' + await post('Redactor', 'create', JSON.stringify( test ) ) ); 
    }

    serialize(){
        let id = 0;
        let pageid = 0;
        $('.task').each( function(){
            $(this).find('.question').each( function(){
                $(this).attr({'answerid':id,'pageid':pageid,'name':pageid});
                 id++; 
            } );
            id = 0;
            pageid++;
        } );
    }

    getAnswers(){
        let answers = [ [] ];
        let id = 0;

        $('.task').each( function(){
            $(this).find('.question').each( function(){
                answers[id].push( getVal( $(this) ) );
            } );
            id++;
            answers.push([]);
        } );


        answers.pop();
        return answers;
    }

    getText(){
        let text = [];

        $('.task').each( function(){
            text.push(`<div class="task">${$(this).html()}</div>`);
        } );


        return text;
        
    }

    async edit(){
        //get data
        let test;
        try{
            test = JSON.parse( await post( 'Redactor', 'get', JSON.stringify( { publicid: document.location.pathname.split("/").pop() } ) ) );
        }
        catch{
            location.assign(baseurl + '/create');
        }
        test.answers = JSON.parse(test.answers);
        test.text = JSON.parse(test.text);

        //tittle
        $('title').html('Редактировать тест');
        //set properties at editor:
        //toggle
        this.toggle = test.visibility;
        if ( this.toggle == false ){
            $('.show-button').val('Тест доступен по ссылке');
        }
        else{
            $('.show-button').val('Тест доступен всем');
        }
        //name
        $('#name').val(test.name);
        $('#time').val(test.time);
        $('.task-wrap').detach();
        //id
        this.updateId = test.publicid;
        //text
        test.text.forEach( (item,index) => {
            $('.editor').append(`<div taskid="${index}" class="task-wrap">${item}<div class="delete-task"></div></div>`);
            $('.delete-task').css({'background-image':`url(${close})`});
            $('.delete-task').on('click', function(){
                $(this).parent().animate({ opacity: 'hide' }, 500, function(){
                    $(this).detach();
                } );
            });
        });
        //add open button
        $('.finale-options').append(`<a href="${baseurl}/launch/${test.publicid}"><input type="button"
        style="display: inline; padding: 0 2px 0 2px; margin-top: 20px; background:#3048ef; border: 1px solid #3048ef;" value="Открыть" class="button openBut"></a>`);
        //answers
        this.pasteAnswers(test.answers);
        

    }
    async update(){
        let answers = this.getAnswers();
        $('.question').each( function(){ getVal($(this), ''); } );

        $('body').prepend( await post( 'Redactor', 'edit', JSON.stringify( { name: $('#name').val(), show: this.toggle, text: this.getText(), answers: answers, publicid: this.updateId, time: $('#time').val() } ) )  );
        setTimeout(() => {
            $('.error').animate({opacity:0})
            $('.massage').animate({opacity:0})
        }, 2000); 

        this.pasteAnswers(answers);
        $('.question').each( function(){
            if ( $(this).attr('check') === 'true' ) $(this).trigger('click');
        } );
    }

    pasteAnswers(answers){
        $('.task').each( function(){
            $(this).attr('Contenteditable',"true")
            $(this).find('.question').each( function(){
                getVal( $(this), answers[ $(this).attr('pageid') ][ $(this).attr('answerid') ] );
                if ( $(this).attr('check') === 'true' ) $(this).trigger('click');
            } );

        } );
    }
}

