<div class="modal" id="modal">
  <div class="modal-background"></div>
  <div class="modal-content">
    <div class="box">
      <h1 class="subtitle is-5" id="modal_title"></h1>
      <hr />
      <p style="margin-bottom:12px" id="modal_body"></p>
      <input id="uid" type="hidden" />
      <div class="field is-grouped is-grouped-right">
        <p class="control">
          <button class="button is-light" onclick="toggleModal('modal')">
            Cancel
          </button>
        </p>
        <p class="control">
          <a class="button is-danger" id="action_btn" onclick="performAction()"></a>
        </p>
      </div>
    </div>
  </div>
  <button class="modal-close is-large" aria-label="close" onclick="toggleModal()"></button>
</div>
