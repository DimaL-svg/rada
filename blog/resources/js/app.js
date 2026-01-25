import './bootstrap';
import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import ImageTool from '@editorjs/image';
import Alpine from 'alpinejs';

window.EditorJS = EditorJS;
window.Header = Header;
window.ImageTool = ImageTool
window.Alpine = Alpine;

Alpine.start();
