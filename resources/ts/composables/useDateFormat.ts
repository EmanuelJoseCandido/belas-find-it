// resources/ts/composables/useDateFormat.ts
import dayjs from "@/plugins/dayjs";

export function useDateFormat() {
  // Formata a data no padrão DD/MM/YYYY
  const formatDate = (date: string | Date | null): string => {
    if (!date) return "N/A";
    return dayjs(date).format("DD/MM/YYYY");
  };

  // Formata a data e hora no padrão DD/MM/YYYY HH:mm
  const formatDateTime = (date: string | Date | null): string => {
    if (!date) return "N/A";
    return dayjs(date).format("DD/MM/YYYY HH:mm");
  };

  // Retorna tempo relativo (ex: "há 2 dias", "há 3 horas")
  const timeAgo = (date: string | Date | null): string => {
    if (!date) return "N/A";
    return dayjs(date).fromNow();
  };

  // Retorna a data em formato personalizado
  const formatCustom = (date: string | Date | null, format: string): string => {
    if (!date) return "N/A";
    return dayjs(date).format(format);
  };

  return {
    formatDate,
    formatDateTime,
    timeAgo,
    formatCustom,
    dayjs,
  };
}
