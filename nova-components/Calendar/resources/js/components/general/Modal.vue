<template>
    <div class="modal-overlay" >
        <div class="modal">
            <slot name="title"></slot>
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {

}
</script>

<style  scoped>
.modal-overlay {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    display: flex;
    flex-flow: row wrap;
    justify-content: center;
    align-items: center;
    z-index: 100;
    justify-content: center;
    background-color: #000000da;
}

.modal {
    text-align: center;
    background-color: white;
    height: auto;
    padding: 2rem;
    width: 500px;
    margin: 10%;
    border-radius: 20px;

}



.check {
    width: 150px;
}

h6 {
    font-weight: 500;
    font-size: 28px;
    margin: 20px 0;
}

p {
    font-size: 16px;
    margin: 20px 0;
}

button {
    background-color: #ac003e;
    width: 150px;
    height: 40px;
    color: white;
    font-size: 14px;
    border-radius: 16px;
    margin-top: 50px;
}
</style>
