<template id="message-template">
    <div class="message">
        <div>
            <span class="msg-head">
                <span class="name">Name</span>
                à
                <span class="date">Date</span>
            </span>
        </div>
        <div>
            <div class="content">Content</div>
        </div>
    </div>
</template>

<div class="collection">

</div>

<form action="msg/send/1" method="post" id="message-form">
    <input type="text" name="content">
</form>