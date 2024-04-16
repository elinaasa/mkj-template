'use strict';

const modalButtons = document.querySelectorAll('.open-modal');
const modal = document.querySelector('#singlePost');
const closeButton = document.querySelector('#close');
const modalContent = document.querySelector('.modal-content');
modalButtons.forEach(button => {
    button.addEventListener('click', async (evt) => {
        evt.preventDefault();
        const url = singlePost.ajax_url;
        const data = new URLSearchParams({
            action: 'single_post',
            post_id: button.dataset.id
        });
        const options = {
            method: 'POST',
            body: data,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            }
        };
        const response =  await fetch(url, options);
        const post = response.json();
        console.log(post);

        modalContent.innerHTML = '';
        modalContent.insertAdjacentHTML('afterbegin', post_title);
        modalContent.insertAdjacentHTML('beforeend', post_content);
        modal.showModal();
    });
});

closeButton.addEventListener('click', () => {
    modal.close();

});