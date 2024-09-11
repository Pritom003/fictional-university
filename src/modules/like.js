import $ from 'jquery';

class Like {
    constructor() {
        this.events();
    }

    events() {
        $('.like-box').on('click', this.ourClickDispatcher.bind(this));
    }

    // methods
    ourClickDispatcher(e) {
        var likeBox = $(e.target).closest(".like-box");

        if (likeBox.data('exists') == 'yes') {
            this.deleteLike(likeBox);
        } else {
            this.createLike(likeBox);
        }
    }
createLike(likeBox) {
    $.ajax({
        beforeSend: (xhr) => {
            console.log('Nonce:', universityData.nonce);  // Log nonce
            xhr.setRequestHeader('X-WP-Nonce', universityData.nonce);
        },
        url: universityData.root_url + '/wp-json/university/v1/managelike',
        type: 'POST',
        data: {
            'professorId': likeBox.data('professor')
        },
        xhrFields: {
            withCredentials: true
        },
        success: (response) => {
            console.log('Success:', response);
            likeBox.attr('data-exists','yes');
            var likeCount=parseInt(likeBox.find(".like-count").html(),10);
           likeCount++;
           likeBox.find(".like-count").html(likeCount);
        },
        error: (response) => {
            console.log('Error:', response);
        }
    });
}

    
    deleteLike(likeBox) {
        $.ajax({
            url:universityData.root_url + '/wp-json/university/v1/managelike',
            type: 'DELETE',
            success: (response) => {
                console.log(response);
            },
            error: (response) => {
                console.log(response);
            }
        });
    }
}

export default Like;
