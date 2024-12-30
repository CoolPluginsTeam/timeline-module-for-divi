// import { staticModule, staticModuleMetadata } from "./static-module";
import {timeline,timelineMetadata} from "./Timeline";
import {timelineChild,timelineChildMetadata} from "./Timeline-item";

const { registerModule } = window.divi.moduleLibrary;

const {
  addAction,
} = window?.vendor?.wp?.hooks;


addAction('divi.moduleLibrary.registerModuleLibraryStore.after', 'dtmc', () => {
  // registerModule(staticModuleMetadata, staticModule);
  registerModule(timelineMetadata, timeline);
  registerModule(timelineChildMetadata, timelineChild);
});