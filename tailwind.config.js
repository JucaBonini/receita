/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.php",
    "./assets/js/**/*.js"
  ],
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        "primary": "#ec5b13",
        "background-light": "#f8f6f6",
        "background-dark": "#221610",
      },
      fontFamily: {
        "display": ["Public Sans", "sans-serif"]
      },
      borderRadius: {
        "DEFAULT": "0.25rem",
        "lg": "0.5rem",
        "xl": "0.75rem",
        "full": "9999px"
      },
      typography: (theme) => ({
        DEFAULT: {
          css: {
            color: "#334155", // slate-700
            '--tw-prose-headings': "#0f172a", // slate-900
            '--tw-prose-links': "#ec5b13", // primary
            '--tw-prose-bullets': "#ec5b13",
            h1: { fontWeight: '900', },
            h2: { fontWeight: '800', marginTop: '2em', marginBottom: '1em' },
            h3: { fontWeight: '700', },
            a: { textDecoration: 'none', fontWeight: '700', '&:hover': { textDecoration: 'underline' } },
          },
        },
        invert: {
          css: {
            color: "#cbd5e1", // slate-300 para legibilidade extrema
            '--tw-prose-headings': "#ffffff",
            '--tw-prose-links': "#ec5b13", // primary mantém ou pode clarear um pouco
            '--tw-prose-bullets': "#ec5b13",
            '--tw-prose-bold': "#ffffff",
            '--tw-prose-quotes': "#e2e8f0",
          },
        },
      }),
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('@tailwindcss/container-queries'),
  ],
}
