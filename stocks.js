var stocksRDB = document.querySelectorAll('.stocks')
var reason = document.querySelector('#reason')

stocksRDB.forEach(rdb => {
    rdb.addEventListener('click', function(){
        if(this.value == 'in'){
            reason.classList.add('d-none')
        }else{
            reason.classList.remove('d-none')
        }
    })
})

