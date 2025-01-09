import { format } from "date-fns";

export function formatDateTime(date) {
    return format(new Date(date), 'dd/MM/yyyy HH:mm');
}