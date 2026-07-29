(async () => {
  // Prefer reading Base64 from the script tag attribute to avoid inline-script CSP issues
  const currentScript = document.currentScript || document.querySelector('script[src*="/assets/encrypt.js"]');
  const b64 = (currentScript && currentScript.getAttribute('data-html-b64')) || (window.__HTML_B64__ || '');

  // If Web Crypto isn't available (e.g., non-HTTPS), render directly to avoid a blank page
  // If no payload, render nothing (feature disabled)
  if (!b64) return;

  if (!('crypto' in window) || !window.crypto.subtle || !window.isSecureContext) {
    const html = b64 ? atob(b64) : '';
    document.open();
    document.write(html);
    document.close();
    return;
  }

  const b64ToBytes = (b64str) => Uint8Array.from(atob(b64str), c => c.charCodeAt(0));
  const dec = new TextDecoder();

  const plaintext = b64ToBytes(b64);

  const keyMaterial = crypto.getRandomValues(new Uint8Array(32));
  const key = await crypto.subtle.importKey('raw', keyMaterial, { name: 'AES-GCM' }, false, ['encrypt','decrypt']);
  const iv = crypto.getRandomValues(new Uint8Array(12));

  const cipherBuf = await crypto.subtle.encrypt({ name: 'AES-GCM', iv }, key, plaintext);
  const cipherBytes = new Uint8Array(cipherBuf);

  const importedKey = await crypto.subtle.importKey('raw', keyMaterial, { name: 'AES-GCM' }, false, ['decrypt']);
  const decrypted = await crypto.subtle.decrypt({ name: 'AES-GCM', iv }, importedKey, cipherBytes);
  const html = dec.decode(new Uint8Array(decrypted));

  document.open();
  document.write(html);
  document.close();
})().catch(err => {
  console.error(err);
  document.open();
  document.write('Decrypt error');
  document.close();
});


