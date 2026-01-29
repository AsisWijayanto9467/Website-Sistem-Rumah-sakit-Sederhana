export default {
    content: ["./resources/**/*.blade.php", "./resources/**/*.js"],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: "#eff6ff",
                    100: "#dbeafe",
                    200: "#bfdbfe",
                    300: "#93c5fd",
                    400: "#60a5fa",
                    500: "#3b82f6",
                    600: "#2563eb",
                    700: "#1d4ed8",
                    800: "#1e40af",
                    900: "#1e3a8a",
                },
                sidebar: {
                    bg: "#1e293b",
                    text: "#f1f5f9",
                    hover: "#334155",
                    active: "#3b82f6",
                },
            },
            transitionProperty: {
                height: "height",
                "max-height": "max-height",
                spacing: "margin, padding",
            },
            animation: {
                fadeIn: "fadeIn 0.3s ease-in-out",
                slideDown: "slideDown 0.4s ease-out",
                slideUp: "slideUp 0.3s ease-out",
            },
            keyframes: {
                fadeIn: {
                    "0%": {
                        opacity: "0",
                    },
                    "100%": {
                        opacity: "1",
                    },
                },
                slideDown: {
                    "0%": {
                        transform: "translateY(-10px)",
                        opacity: "0",
                    },
                    "100%": {
                        transform: "translateY(0)",
                        opacity: "1",
                    },
                },
                slideUp: {
                    "0%": {
                        transform: "translateY(0)",
                        opacity: "1",
                    },
                    "100%": {
                        transform: "translateY(-10px)",
                        opacity: "0",
                    },
                },
            },
        },
    },
    plugins: [],
};
