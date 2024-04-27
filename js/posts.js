$(document).ready(() => {
    const createUserAvatar = (username, createdById) => {
        const firstTwoLetters = username.slice(0, 2).toUpperCase();
        const color = getColorFromId(createdById);
        return `
        <div class="avatar" style="background-color: ${color};">
            ${firstTwoLetters}
        </div>
    `;
    };

    const getColorFromId = (createdById) => {
        const id = parseInt(createdById, 10);
        const colors = [
            '#1f77b4', '#aec7e8', '#ff7f0e', '#ffbb78', '#2ca02c', '#98df8a', '#d62728', '#ff9896',
            '#9467bd', '#c5b0d5', '#8c564b', '#c49c94', '#e377c2', '#f7b6d2', '#7f7f7f', '#c7c7c7',
            '#bcbd22', '#dbdb8d', '#17becf', '#9edae5', '#393b79', '#5254a3', '#6b6ecf', '#9c9ede',
            '#637939', '#8ca252', '#b5cf6b', '#cedb9c', '#8c6d31', '#bd9e39', '#e7ba52', '#e7cb94',
            '#843c39', '#ad494a', '#d6616b', '#e7969c', '#7b4173', '#a55194', '#ce6dbd', '#de9ed6'
        ];
        return colors[id % colors.length];
    };

    const createPostCard = (post) => {
        const {createdBy, createdById, content} = post;
        const avatar = createUserAvatar(createdBy, createdById);
        const lines = content.split('\n').length;
        const button = lines > 4 ? `<button class="btn btn-link position-absolute -bottom-0 show-more-btn">Show more</button>` : '';

        return `
        <div class="card mb-3 w-100 shadow-sm border-light-subtle border-1 position-relative p-4">
        <div class="pb-4">
             <div class="card-body">
                <div class="d-flex gap-4 align-items-center mb-3">
                    ${avatar}
                    <h5 class="card-title ml-3">${createdBy}</h5>
                </div>
                <p class="card-text px-4 py-2">${content}</p>
            </div>
            ${button}
            </div>
        </div>
    `;
    };

    $(document).on('click', '.show-more-btn', function () {
        const $cardText = $(this).prev('.card-text');
        const $body = $(this).closest('.card').find('.card-body');
        $cardText.toggleClass('show-all');
        $(this).text(function (i, text) {
            if (text === 'Show more') {
                $body.addClass('normal');
                $body.height($body.get(0).scrollHeight);
                return 'Show less';
            } else {
                $body.removeClass('normal');
                $body.height('');
                return 'Show more';
            }
        });
    });

    $.ajax({
        type: 'GET',
        url: '/web/api/post/all',
        contentType: 'application/json',
        headers: {'Authorization': `Bearer ${localStorage.getItem('token')}`},
        success: (response) => {
            response.forEach((post) => {
                $('#posts').append(createPostCard(post));
            });
        },
        error: ({xhr, status, error}) => {
            console.error({xhr, status, error});
            const errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'Unknown error';
        }
    });

    $('#post-form').validate({
        errorElement: 'small',
        rules: {
            content: {required: true}
        },
        messages: {content: 'Content is required'},
        submitHandler: (form, event) => {
            event.preventDefault();
            const content = $('#postContent').val();
            const formData = {content};

            $.ajax({
                type: 'POST',
                url: '/web/api/post',
                data: JSON.stringify(formData),
                headers: {'Authorization': `Bearer ${localStorage.getItem('token')}`},
                contentType: 'application/json',
                success: (post) => {
                    $('#postContent').val('');
                    $('#posts').prepend(createPostCard(post));
                },
                error: ({xhr, status, error}) => {
                    console.error({xhr, status, error});
                    const errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'Unknown error';
                }
            });
        }
    });
});
