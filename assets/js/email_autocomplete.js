document.addEventListener('DOMContentLoaded', function () {
    const emailInput = document.querySelector('.email-autocomplete');
    const userIdInput = document.querySelector('input[name="userId"]');
    const emailList = document.createElement('ul');
    emailList.id = 'email-list';
    emailList.classList.add('list-group');

    emailInput.parentNode.appendChild(emailList);

    emailInput.addEventListener('input', function () {
        const query = emailInput.value;

        if (query.length < 2) {
            emailList.innerHTML = '';
            return;
        }

        fetch(`/api/users?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                emailList.innerHTML = '';
                data.forEach(user => {
                    const listItem = document.createElement('li');
                    listItem.classList.add('list-group-item');
                    listItem.textContent = user.email;
                    listItem.dataset.id = user.id;

                    listItem.addEventListener('click', function () {
                        emailInput.value = user.email;
                        userIdInput.value = user.id;
                        emailList.innerHTML = '';
                    });

                    emailList.appendChild(listItem);
                });
            });
    });
});