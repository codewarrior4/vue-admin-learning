import toastr from 'toastr'
import 'toastr/build/toastr.min.css'

export function useToastr() {
    toastr.options = {
        closeButton: true,
        debug: false,
        newestOnTop: true,
        progressBar: true,
        positionClass: 'toast-bottom-right',
        preventDuplicates: false, 
        showEasing: 'swing',
        hideEasing: 'linear',
        showMethod: 'fadeIn',
        hideMethod: 'fadeOut'
    }
    return toastr
}