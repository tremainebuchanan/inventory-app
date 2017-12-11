/**
 * Determines if the value of the field is empty.
 * @param  {[type]}  value [description]
 * @return {Boolean}       [description]
 */
function isEmpty( field_id, error_element_id ){
  var err_ele = document.getElementById(error_element_id)
  var field = document.getElementById(field_id)
  if(field.value === ''){
    err_ele.style.display = 'block'
    err_ele.innerHTML = 'The name is of the item is required'
    field.classList.toggle('is-danger')
  }else{
    err_ele.style.display = 'none'
    field.classList.remove('is-danger')
  }
}

function isValidNumber(field_id, error_element_id){
  var err_ele = document.getElementById(error_element_id)
  var field = document.getElementById(field_id)
  if(isNaN(field.value) || field.value === ''){
    err_ele.style.display = 'block'
    err_ele.innerHTML = 'No value entered or the value entered is not a number'
    field.classList.toggle('is-danger')
  }else{
    err_ele.style.display = 'none'
  }
}


function back(){
  window.history.back()
}

function createItem() {
  var form = new FormData(document.getElementById('item-form'))
  var config = { 'method': 'POST' }
  config.body = form
  fetch('api/item.php', config ).then(function(response){
        response.json(response).then(function(data){
            document.getElementById('item-form').reset()
            console.log(data)
            toggle( data )
        })
  })
}

function updateItem() {
  var form = new FormData(document.getElementById('update-form'))
  var config = { 'method': 'POST' }
  config.body = form
  fetch('api/item.php', config ).then(function(response){
        response.json(response).then(function(data){
            document.getElementById('item-form').reset()
            console.log(data)
            toggle( data )
        })
  })
}

function toggle( message ){
  var notification_area = document.getElementById('notification')
  notification_area.style.display = "block"
  notification_area.innerHTML = message.text
  message.success === true ?
      notification_area.className += " is-primary" :
      notification_area.className += " is-danger"
}

function toggleModal() {
  var modal = document.getElementById('modal')
  modal.className.indexOf("is-active") === -1 ?
      modal.className += " is-active" : modal.classList.remove('is-active')
}

function getUser(id, action){
  var config = { 'method': 'GET' }
  fetch('api/fetch-user.php?id='+id, config ).then(function(response){
        response.json(response).then(function(data){
            document.getElementById('uid').value = data.id
            var modalAttributes = buildModal(action)
            modalAttributes.username = data.name
            setModal(modalAttributes)
            toggleModal()
        })
  })
}

function buildModal( action ){
  var actions = {}
  actions.suspend = {'title': 'Suspend User',
                    'body': 'Are you sure you want to suspend the account of',
                     'action': 'Suspend'}
  actions.delete = {'title': 'Delete User', 'body': 'Are you sure you want to delete the account of', 'action': 'Delete'}
  actions.activate = {'title': 'Activate User', 'body': 'Are you sure you want to activate the account of', 'action': 'Activate'}
  return actions[action]
}

function setModal( attributes ){
  document.getElementById('action_btn').innerHTML = attributes.action
  document.getElementById('modal_title').innerHTML = attributes.title
  document.getElementById('modal_body').innerHTML = attributes.body + ' ' + '<strong>'+ attributes.username +'</strong>'
}

function performAction(){
  var action = document.getElementById('action_btn').innerHTML
  switch (action) {
    case 'Suspend': updateUser(1, 'user_suspended')
      break;
    case 'Activate': updateUser(2, 'user_activated')
      break;
    case 'Delete': deleteUser()
      break;
    default:
  }
}

function updateUser( value, action_type ){
  var uid = document.getElementById('uid').value
  fetch('api/update-user.php?uid='+uid+'&value=' + value).then(function(response){
        response.json(response).then(function(data){
          console.log(data)
          toggleModal()
          window.location.replace("admin.php?"+action_type+"=true")
      })
  })
}

function deleteUser(){
  var uid = document.getElementById('uid').value
  fetch('api/delete-user.php?id='+uid).then(function(response){
        response.json(response).then(function(data){
          toggleModal()
          window.location.replace("admin.php?user_deleted=true")
        })
  })
}
