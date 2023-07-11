import moment from 'moment'

export function formatDate(value){
    return (value) ? moment(value).format('YYYY-DD-MM') : ''
}   