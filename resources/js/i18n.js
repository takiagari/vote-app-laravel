import { createI18n } from 'vue-i18n';

// `ja.json` ファイルをインポート
import ja from '../../lang/ja.json';

const messages = {
  ja: ja
};

const i18n = createI18n({
  locale: 'ja', // デフォルトロケールを日本語に設定
  fallbackLocale: 'ja',
  messages,
  legacy: false, // レガシーモードを無効化
  globalInjection: true // `t` メソッドをグローバルに注入
});

export default i18n;
