import $ from 'jquery'

export default async function(page,id, word){
    let answer = await $.ajax({
        url: '../../manager/check.php',
        method: 'post',
        data: {indeficator: location.search.slice(1),
            page:page,
            id:id,
            answer:word,
        },
    });
    return answer
}