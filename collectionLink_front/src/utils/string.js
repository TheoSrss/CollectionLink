export const truncateText = (text, maxLength = 50, suffix = "...") => {
    if (!text) return suffix;
    return text.length > maxLength ? `${text.slice(0, maxLength)}${suffix}` : text;
};