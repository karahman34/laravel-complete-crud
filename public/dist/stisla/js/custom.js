/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

const CSRF_TOKEN = document.querySelector('meta[name=csrf-token]').getAttribute('content')

function removeValidationErrors($form) {
  $form.find('.form-group .form-control').removeClass('is-valid')
  $form.find('.form-group .form-control').removeClass('is-invalid')
  $form.find('.form-group .text-danger').remove()
}

function showValidationErrors(errorFields, $form) {
  const errorFieldsName = Object.keys(errorFields)
  const $fields = $form.find('.form-group input')

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
}

function ButtonSpinner($btn) {
  this.$btn = $btn
  this.width = this.$btn.width()
  this.content = this.$btn.html()

  this.show = () => {
    this.$btn.html(`<i class="fas fa-spinner fa-spin"></i>`)
    this.$btn.width(this.width)
  }

  this.hide = () => this.$btn.html(this.content)
}

// Logout
const logoutButtons = document.querySelectorAll('.logout-btn')
const logoutForm = document.querySelector('#logout-form')
for (let i = 0; i < logoutButtons.length; i++) {
  logoutButtons[i].addEventListener('click', () => logoutForm.submit())
}

// Delete Promp
$('body').on('click', '.delete-prompt-trigger', function (e) {
  e.preventDefault()

  const $btn = $(this)
  const itemName = $btn.data('item-name')

  Swal.fire({
    icon: 'warning',
    title: `Are you sure want to delete "${itemName}" ?`,
    text: "You will not be able to recover this imaginary file!",
    reverseButtons: true,
    showCancelButton: true,
    confirmButtonColor: '#fb3838',
    confirmButtonText: 'Yes, delete it!',
    preConfirm: () => {
      Swal.showLoading()
    },
    allowOutsideClick: () => !Swal.isLoading(),
  }).then(({ isConfirmed }) => {
    if (isConfirmed) {
      const url = $btn.attr('href')

      $.post(url, { _token: CSRF_TOKEN, _method: 'DELETE' })
        .done(() => {
          Swal.fire({
            icon: 'success',
            title: 'Complete!',
            text: `"${itemName}" has been deleted.`,
          })

          if ($btn.hasClass('has-datatable')) {
            $($btn.data('datatable')).DataTable().ajax.reload(null, false)
          }
        })
        .fail(() => Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Failed to delete item.',
        }))
    }
  })
})

// Modal Api Trigger
$('body').on('click', '.btn-modal-trigger[data-modal]', function (e) {
  e.preventDefault()

  const $btn = $(this)
  const url = $btn.attr('href')
  const modalSelector = $btn.data('modal')

  // Set loading spinner
  const $btnSpinner = new ButtonSpinner($btn)
  $btnSpinner.show()

  $.get(url, function (res) {
    $('#app').append(res)
    const $modal = $(`.modal${modalSelector}`)
    $modal.modal('show')
    $modal.on('hidden.bs.modal', function () {
      $modal.remove()
    })
  })
    .fail(() => Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Failed to open modal.',
    }))
    .always(() => $btnSpinner.hide())
})

// Export Modal Form Submit
$('body').on('submit', '.export-modal form', function (e) {
  e.preventDefault()
  const $form = $(this)
  const url = $form.attr('action')
  const data = $form.serialize()

  // Check format
  const $format = $form.find('.form-group #format')
  $form.find('.text-danger').remove()
  if (!$format.val() || !$format.val().length) {
    $format.after('<p class="text-danger">The format field is required.</p>')
    return
  }

  // Open export in new tab
  window.open(`${url}?${data}&export=1`)
})

// Normal Form
$('body').on('submit', 'form.need-ajax', function (e) {
  e.preventDefault()

  const $form = $(this)
  const url = $form.attr('action')
  const method = $form.attr('method')
  const enctype = $form.attr('enctype')
  const data = enctype === 'multipart/form-data' ? new FormData(this) : $form.serialize()

  const options = {
    url,
    data,
    type: method,
    success: res => {
      // Close Modal
      if ($form.hasClass('has-modal')) {
        const $modal = $form.closest('.modal')
        $modal.modal('hide')
        $modal.on('hidden.bs.modal', () => $modal.remove())
      }

      // Refresh / Redraw data Table
      if ($form.hasClass('has-datatable')) {
        const $dataTable = $($form.data('datatable'))
        const stayPaging = $form.data('stay-paging')

        stayPaging == '1'
          ? $dataTable.DataTable().ajax.reload(null, false)
          : $dataTable.DataTable().order([0, 'desc']).draw()
      }

      Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: res.message || 'Action success!',
      })
    },
    error: err => {
      const errCode = err.status
      const errData = err.responseJSON

      // Validation Error
      if (errCode === 422) {
        showValidationErrors(errData.errors, $form)
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Error while submitting data.',
        })
      }
    }
  }

  if (enctype === 'multipart/form-data') {
    options['cache'] = false
    options['contentType'] = false
    options['processData'] = false
  }

  // Set Loading
  const $btnSpinner = new ButtonSpinner($form.find('button[type=submit]'))
  $btnSpinner.show()

  removeValidationErrors($form)

  $.ajax(options)
    .always(() => $btnSpinner.hide())
})