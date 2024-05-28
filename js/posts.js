$(document).ready(() => {
    if (location.href.includes('?id=')) {
        const urlWithoutQueryParam = location.href.split('?')[0];
        location.replace(urlWithoutQueryParam);
    }
    $('#post-form').validate({
        errorElement: 'small',
        rules: {
            content: {required: true}
        },
        messages: {content: 'Content is required'},
        submitHandler: (form, event) => {
            event.preventDefault();
            const content = $('#content').val();
            if (!content) return
            const formData = {content};
            $.ajax({
                type: 'POST',
                url: '/web/api/post',
                data: JSON.stringify(formData),
                headers: {'Authorization': `Bearer ${localStorage.getItem('token')}`},
                contentType: 'application/json',
                success: (post) => {
                    $('#content').val('');
                    $('#posts').prepend(createPostCard(post));
                },
                error: ({xhr, status, error}) => {
                    console.error({xhr, status, error});
                    const errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'Unknown error';
                }
            });
        }
    });

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
        const {createdBy, createdById, content, id, likes, isLiked} = post;
        const avatar = createUserAvatar(createdBy, createdById);
        const lines = content.split('\n').length;
        const button = lines > 4 ? `<img alt="" height="60" width="60" src="public/down.svg" class="btn btn-link position-absolute show-more-btn"/>` : '';
        const likeButton = `<img id=${id} class="icon" src=${isLiked ? "public/liked.svg" :"public/unliked.svg"} alt="" height="24" width="24" />`
        return `
        <div class="card mb-3 w-100 shadow-sm border-light-subtle border-1 position-relative p-4">
        <div class="pb-5" id="post-${id}">
             <div class="card-body">
                <div class="d-flex gap-4 align-items-center mb-3">
                    ${avatar}
                    <h5 class="card-title ml-3">${createdBy}</h5>
                </div>
                <p class="card-text px-4 py-2">${content}</p>
            </div>
            </div>
             ${button}
             <div class="px-4">
                ${likeButton}
                <span id="likes">${likes}</span>
             </div>
        </div>
    `;
    };

    $(window).on('popstate', () => location.reload());

    $(document).on('click', '.icon', function () {
        const icon = $(`#${$(this).attr('id')}`)
        const text = icon.next($('#likes'))

        console.log($(this).attr('id'))
        $.ajax({
            type: 'PATCH',
            url: `/web/api/post/like?id=${$(this).attr('id')}`,
            contentType: 'application/json',
            headers: {'Authorization': `Bearer ${localStorage.getItem('token')}`},
            success: (response) => {
                if (response) {
                    text.text(parseInt(text.text()) + 1)
                    icon.attr('src', 'public/liked.svg')
                } else {
                    text.text(parseInt(text.text()) - 1)
                    icon.attr('src', 'public/unliked.svg')
                }
            },
            error: ({xhr, status, error}) => {
                console.error({xhr, status, error});
                const errorMessage = xhr.responseText ? JSON.parse(xhr.responseText).message : 'Unknown error';
            }
        })
    })


    $(document).on('click', 'div[id^=post-]', function () {
        const postId = $(this).attr('id')?.split('-')?.[1];
        const url = `?id=${postId}#thread`;
        history.pushState(null, null, url);
        location.reload()

    });

    $(document).on('click', '.show-more-btn', function () {
        const $body = $(this).closest('.card').find('.card-body');
        const $cardText = $body.find('.card-text');
        console.log($(this).attr('src'))
        $cardText.toggleClass('show-all');
        if ($(this).attr('src').includes('down')) {
            $(this).attr('src', 'public/up.svg')
            $body.addClass('normal');
            $body.height($body.get(0).scrollHeight);
            return 'Show less';
        } else {
            $(this).attr('src', 'public/down.svg')
            $body.removeClass('normal');
            $body.height('');
            return 'Show more';
        }

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

});
