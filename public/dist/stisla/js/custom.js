/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

const CSRF_TOKEN = document.querySelector('meta[name=csrf-token]').getAttribute('content')

// Logout
const logoutButtons = document.querySelectorAll('.logout-btn')
const logoutForm = document.querySelector('#logout-form')
for (let i = 0; i < logoutButtons.length; i++) {
  logoutButtons[i].addEventListener('click', () => logoutForm.submit())
}

// Form Modal Trigger
$('body').on('click', '.form-modal-trigger', function (e) {
  e.preventDefault()
  const $formModal = $('#form-modal')
  const $btn = $(this)
  const title = $btn.data('modal-title')
  const action = $btn.data('modal-action')
  const href = $btn.attr('href')

  const prevContent = $btn.html()
  $btn.html('<i class="fas fa-spinner fa-spin"></i>')

  $.get(href, (res) => {
    $formModal.find('.modal-body').html(res)

    if (title) {
      $formModal.find('.modal-title').html(title)
    }

    if (action) {
      // Set submit text
      $formModal.find('.btn-submit').html(action.charAt(0).toUpperCase() + action.slice(1))

      // Set button type
      $formModal.find('.btn-submit').addClass(action.toLowerCase() === 'create' ? 'btn-primary' : 'btn-warning')
    }

    $formModal.modal('toggle')
  })
    .fail(() => alert('Failed to get modal data.'))
    .always(() => $btn.html(prevContent))
})

// Form Button Submit
$('#form-modal .btn-submit').click(function (e) {
  e.preventDefault()
  $('#form-modal form').submit()
})

//Form Modal Submit
$('body').on('submit', '#form-modal form', function (e) {
  e.preventDefault()
  const $form = $(this)
  const url = $form.attr('action')
  const data = new FormData(this)
  const $formModal = $('#form-modal')

  // Remove previous errors
  $formModal.find('.form-group input').removeClass('is-valid')
  $formModal.find('.form-group input').removeClass('is-invalid')
  $formModal.find('.form-group .text-danger').remove()

  // Set loading
  const submitBtnContent = $formModal.find('.btn-submit').html()
  $formModal.find('.btn-submit').html(`
    <i class="fas fa-spinner fa-spin"></i>
  `)

  $.ajax({
    url,
    data,
    type: 'POST',
    cache: false,
    contentType: false,
    processData: false,
    success: () => {
      const dataTableSelector = $form.data('table-selector')
      if (dataTableSelector) {
        const stayPaging = $form.data('form-action').toLowerCase() == 'patch'
        const $dataTable = $(dataTableSelector)

        stayPaging
          ? $dataTable.DataTable().ajax.reload(null, false)
          : $dataTable.DataTable().order([0, 'desc']).draw()
      }

      $formModal.modal('hide')

      Swal.fire({
        icon: 'success',
        title: 'Complete!',
        text: 'The action has been executed successfully!',
      })
    },
    error: err => {
      const errCode = err.status
      const errData = err.responseJSON

      // Validation Error
      if (errCode === 422) {
        const errorFields = errData.errors
        const errorFieldsName = Object.keys(errorFields)
        const $fields = $formModal.find('.form-group .form-control')

        $fields.each(function (i) {
          const $field = $(this)
          const name = $field.attr('name')
          if (errorFieldsName.includes(name)) {
            // Set error field
            $field.addClass('is-invalid')

            // Show error message
            $field.closest('.form-group').append(`
              <p class="text-danger mb-0">${errorFields[name]}</p>
            `)
          } else {
            $field.addClass('is-valid')
          }
        })
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Error while submitting data.',
        })
      }
    }
  })
    .always(() => $formModal.find('.btn-submit').html(submitBtnContent))
})

// Delete Promp
$('body').on('click', '.delete-prompt-trigger', function (e) {
  e.preventDefault()

  const $btn = $(this)
  const itemName = $btn.data('item-name')

  Swal.fire({
    icon: 'warning',
    title: `Are you sure want to delete ${itemName}?`,
    text: "You will not be able to recover this imaginary file!",
    reverseButtons: true,
    showCancelButton: true,
    confirmButtonColor: '#fb3838',
    confirmButtonText: 'Yes, delete it!'
  }).then(({ isConfirmed }) => {
    if (isConfirmed) {
      const url = $btn.attr('href')

      $.post(url, { _token: CSRF_TOKEN, _method: 'DELETE' })
        .done(() => {
          Swal.fire({
            icon: 'success',
            title: 'Complete!',
            text: `${itemName} has been deleted.`,
          })

          $($btn.data('table-selector')).DataTable().ajax.reload(null, false)
        })
        .fail(() => Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Failed to delete item.',
        }))
    }
  })
})