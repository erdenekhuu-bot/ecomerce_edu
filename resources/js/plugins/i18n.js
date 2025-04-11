import { createI18n } from "vue-i18n";

// const i18n = new VueI18n({
//     locale: 'en',
//     fallbackLocale: 'en',
//     silentTranslationWarn: true
// })

let shopSelectedLanguage = localStorage.getItem("shopSelectedLanguage");

const i18n = createI18n({
    locale: shopSelectedLanguage ?? "en",
    fallbackLocale: shopSelectedLanguage ?? "en",
    silentTranslationWarn: true,
});
const loadedLanguages = [];

function setI18nLanguage(lang, data) {
    loadedLanguages.push(lang);

    i18n.locale = lang;
    i18n.global.setLocaleMessage(lang, data);

    // document.querySelector('html').setAttribute('lang', lang)
    // return lang
}

async function loadLanguageAsync(lang) {
    if (loadedLanguages.includes(lang)) {
        if (i18n.locale !== lang) setI18nLanguage(lang);
        return Promise.resolve();
    }
    const response = await axios.get(`/api/v1/locale/${lang}`);
    if (response.status === 200) {
        setI18nLanguage(lang, response.data);
    }
}

export { i18n, loadLanguageAsync, setI18nLanguage };
