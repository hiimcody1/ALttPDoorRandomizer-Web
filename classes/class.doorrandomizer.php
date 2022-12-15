<?php
/*
 * File: class.doorrandomizer.php
 * File Created: Tuesday, 26th July 2022 7:39:17 pm
 * Author: hiimcody1
 * 
 * Last Modified: Thursday, 15th December 2022 3:41:54 pm
 * Modified By: hiimcody1
 * 
 * License: MIT License http://www.opensource.org/licenses/MIT
 */

class DoorRandomizer {
    public array        $Options;
    public string       $BasePath;

    public function __construct($DoorRandomizerBasePath="") {
        $this->BasePath = $DoorRandomizerBasePath;
        $this->extractOptions();
    }

    public function Generate($Hash,$Options) {
        $Arguments = $this->setupArguments($Options);
        $doorRando = $this->spawnDoorRandomizer($Arguments,$Hash);
        if(Config::Debug) {
            var_export($doorRando->Status());
            $doorRando->ReadPipes();
        }
    }

    private function initTypesFromDoors() {
        //TODO is there a better way to handle dynamic assignment of these settings?
    }

    private function extractOptions() {
        $RawOptions = json_decode(file_get_contents($this->BasePath."resources/app/cli/args.json"), true);
        $RawHelp    = json_decode(file_get_contents($this->BasePath."resources/app/cli/lang/en.json"), true);
        $RawDefaults= json_decode($this->parseCLIPy(), true);
        $this->Options = Array();
        foreach($RawOptions as $Key=>$Options) {

            //Option has a type, appears to be used for booleans only?
            if(isset($Options['type'])) {
                switch($Options['type']) {
                    case "bool":
                        if(isset($Options['dest']) && !isset($RawDefaults[$Key]))
                                break;
                        $NewOption = new DoorRandomizerOption($Key,@$RawHelp['help'][$Key]);
                        $NewOption->Type = "bool";
                        $NewOption->DefaultValue = ($RawDefaults[$Key] == "true" ? true : false);
                        $NewOption->Description = str_replace("%(default)s",($RawDefaults[$Key] == "true" ? "True" : "False"),$NewOption->Description);
                        break;
                    default:
                        break;
                }
            }

            //This is a multiple choice option
            if(isset($Options['choices'])) {
                $NewOption = new DoorRandomizerOptionMultiChoice($Key,@$RawHelp['help'][$Key]);
                $NewOption->Type = "multi";
                foreach($Options['choices'] as $Choice)
                    $NewOption->Choices[]=$Choice;
                $NewOption->DefaultValue = @$RawDefaults[$Key];
                $NewOption->Description = str_replace("%(default)s",$NewOption->DefaultValue,$NewOption->Description);
            }

            //This option has no type and is not multiple choice
            if(!isset($Options['type']) && !isset($Options['choices'])) {
                $NewOption = new DoorRandomizerOption($Key,@$RawHelp['help'][$Key]);
                $NewOption->Type = "string";
                $NewOption->DefaultValue = @$RawDefaults[$Key];
                $NewOption->Description = str_replace("%(default)s",$NewOption->DefaultValue,$NewOption->Description);
            }
            
            //Did we end up resolving this to an option?
            if(@$NewOption !== null && isset($NewOption->Type)) {
                $NewOption->Value = $NewOption->DefaultValue;
                $this->Options[$NewOption->Name] = $NewOption;
            } else {
                echo "This isn't an option for some reason:<br />";
                var_export($NewOption);
            }
            $NewOption=null;
        }
    }

    private function parseCLIPy() {
        //TODO Find a less horrible way to do this.
        $RawCLI  = file_get_contents($this->BasePath."CLI.py");
        $Matches = Array();
        preg_match("/\ssettings \= (\{[\s\S]*\})[\s\S]*if sys/",$RawCLI, $Matches);
        //"/os\.path\.join\(.+\)/"
        $PseudoJson = preg_replace(Array("/os\.path\.join\(.+\)/","/[\s]+\#.+\n/","/None/","/False/","/True/","/\'/"),Array("\"\"","\n","\"\"","false","true","\""),$Matches[1]);
        return $PseudoJson;
    }

    public function parseGUITabs() {
        //TODO - Remove dependency on hard-coded tabs and layout fields
        $Tabs = Array();
        $Tabs["Custom"]         = json_decode(file_get_contents($this->BasePath."resources/app/gui/custom/overview/widgets.json"),          true);
        $Tabs["Dungeon"]        = json_decode(file_get_contents($this->BasePath."resources/app/gui/randomize/dungeon/widgets.json"),        true);
        $Tabs["Enemizer"]       = json_decode(file_get_contents($this->BasePath."resources/app/gui/randomize/enemizer/widgets.json"),       true);
        $Tabs["Entrando"]       = json_decode(file_get_contents($this->BasePath."resources/app/gui/randomize/entrando/widgets.json"),       true);
        $Tabs["GameOptions"]    = json_decode(file_get_contents($this->BasePath."resources/app/gui/randomize/gameoptions/widgets.json"),    true);
        $Tabs["Generation"]     = json_decode(file_get_contents($this->BasePath."resources/app/gui/randomize/generation/widgets.json"),     true);
        $Tabs["Item"]           = json_decode(file_get_contents($this->BasePath."resources/app/gui/randomize/item/widgets.json"),           true);
        $Tabs["Multiworld"]     = json_decode(file_get_contents($this->BasePath."resources/app/gui/randomize/multiworld/widgets.json"),     true);
        try {
            $Tabs["Overworld"]     = json_decode(file_get_contents($this->BasePath."resources/app/gui/randomize/overworld/widgets.json"),     true);
        } catch (Exception $e) {
            //Silently fail, some branches don't have this!
        }
        
        //TODO, fix this forced garbage
        $Tabs["Dungeon"]["widgets"]["door_shuffle"] = $Tabs["Dungeon"]["widgets"]["dungeondoorshuffle"];
        $Tabs["Dungeon"]["widgets"]["intensity"] = $Tabs["Dungeon"]["widgets"]["dungeonintensity"];

        $Tabs["Entrando"]["widgets"]["shuffle"] = $Tabs["Entrando"]["widgets"]["entranceshuffle"];

        $Tabs["Enemizer"]["leftEnemizerFrame"]["shufflebosses"] = $Tabs["Enemizer"]["leftEnemizerFrame"]["bossshuffle"];
        $Tabs["Enemizer"]["leftEnemizerFrame"]["shuffleenemies"] = $Tabs["Enemizer"]["leftEnemizerFrame"]["enemyshuffle"];
        
        $Tabs["Enemizer"]["rightEnemizerFrame"]["enemy_health"] = $Tabs["Enemizer"]["rightEnemizerFrame"]["enemyhealth"];
        $Tabs["Enemizer"]["rightEnemizerFrame"]["enemy_damage"] = $Tabs["Enemizer"]["rightEnemizerFrame"]["enemydamage"];

        $Tabs["Item"]["leftItemFrame"]["mode"] = $Tabs["Item"]["leftItemFrame"]["worldstate"];
        $Tabs["Item"]["leftItemFrame"]["logic"] = $Tabs["Item"]["leftItemFrame"]["logiclevel"];
        $Tabs["Item"]["leftItemFrame"]["algorithm"] = $Tabs["Item"]["leftItemFrame"]["sortingalgo"];

        $Tabs["Item"]["rightItemFrame"]["difficulty"] = $Tabs["Item"]["rightItemFrame"]["itempool"];
        $Tabs["Item"]["rightItemFrame"]["item_functionality"] = $Tabs["Item"]["rightItemFrame"]["itemfunction"];
        $Tabs["Item"]["rightItemFrame"]["progressive"] = $Tabs["Item"]["rightItemFrame"]["progressives"];

        $Tabs["Overworld"]["leftOverworldFrame"]["ow_shuffle"] = $Tabs["Overworld"]["leftOverworldFrame"]["overworldshuffle"];
        $Tabs["Overworld"]["leftOverworldFrame"]["ow_mixed"] = $Tabs["Overworld"]["leftOverworldFrame"]["mixed"];
        $Tabs["Overworld"]["leftOverworldFrame"]["ow_crossed"] = $Tabs["Overworld"]["leftOverworldFrame"]["crossed"];
        $Tabs["Overworld"]["leftOverworldFrame"]["ow_whirlpool"] = $Tabs["Overworld"]["leftOverworldFrame"]["whirlpool"];
        $Tabs["Overworld"]["leftOverworldFrame"]["ow_fluteshuffle"] = $Tabs["Overworld"]["leftOverworldFrame"]["overworldflute"];

        $Tabs["Overworld"]["rightOverworldFrame"]["ow_keepsimilar"] = $Tabs["Overworld"]["rightOverworldFrame"]["keepsimilar"];
        $Tabs["Overworld"]["rightOverworldFrame"]["ow_terrain"] = $Tabs["Overworld"]["rightOverworldFrame"]["terrain"];

        foreach($Tabs as $TabName=>$TabValue) {
            foreach($TabValue as $key=>$value) {
                switch($key) {
                    case "checkboxes":
                    case "topOverworldFrame":
                        //Centered Checkbox
                        if(is_array($value)) {
                            //Options
                            foreach($value as $SettingName=>$SettingData) {
                                if(array_key_exists($SettingName,$this->Options)) {
                                    $this->Options[$SettingName]->Category = DoorRandomizerOptionCategories::fromString($TabName);
                                    $this->Options[$SettingName]->Alignment = "top";
                                }
                            }
                        }
                    break;
                    case "leftItemFrame":
                    case "leftEnemizerFrame":
                    case "leftOverworldFrame":
                        //Align-left
                        if(is_array($value)) {
                            //Options
                            foreach($value as $SettingName=>$SettingData) {
                                if(array_key_exists($SettingName,$this->Options)) {
                                    $this->Options[$SettingName]->Category = DoorRandomizerOptionCategories::fromString($TabName);
                                    $this->Options[$SettingName]->Alignment = "left";
                                }
                            }
                        }
                    break;
                    case "rightItemFrame":
                    case "rightEnemizerFrame":
                    case "rightOverworldFrame":
                        //Align-right
                        if(is_array($value)) {
                            //Options
                            foreach($value as $SettingName=>$SettingData) {
                                if(array_key_exists($SettingName,$this->Options)) {
                                    $this->Options[$SettingName]->Category = DoorRandomizerOptionCategories::fromString($TabName);
                                    $this->Options[$SettingName]->Alignment = "right";
                                }
                            }
                        }
                    break;
                    case "widgets":
                        //Centered
                        if(is_array($value)) {
                            //Options
                            foreach($value as $SettingName=>$SettingData) {
                                if(array_key_exists($SettingName,$this->Options)) {
                                    $this->Options[$SettingName]->Category = DoorRandomizerOptionCategories::fromString($TabName);
                                }
                            }
                        }
                    break;
                    case "itemList1":
                        //Bunch of items
                    break;
                    default:
                        //
                }
            }
        }
    }

    private function setupArguments($Options) {
        if(Config::Debug)
            var_export($Options);
        $arguments = Array();
        $arguments[] = Config::Python;  //Our python binary needs to be at the start!
        $arguments[] = Config::ScriptName;
        foreach($Options as $Option)
            $arguments[] = $Option->asCliArg();
        $arguments[] = "--rom ".Config::RomFilePath;
        $arguments[] = "--outputpath ".Config::OutputDir;
        
        return $arguments;
    }

    private function spawnDoorRandomizer($Arguments,$OutputFilename,$Limit=10,$Background=false) {
        $Arguments[] = "--outputname {$OutputFilename}";
        $Arguments[] = "\n";
        return new Process($Arguments,$this->BasePath);
    }
}

class DoorRandomizerOptionCategories {
    //Folder structure of GUI
    //Ignore Adjust/Overview, they will do that post generation

    //custom folder
    const Custom        =   0;

    //Ignore lang folder

    //These are all inside the randomize folder
    const Dungeon       =   1;
    const Enemizer      =   2;
    const Entrando      =   3;
    const GameOptions   =   4;
    const Generation    =   5;
    const Item          =   6;
    const Multiworld    =   7;
    const Overworld     =   8;

    public static function fromString($name) {
        switch(strtolower($name)) {
            case "dungeon":
                return DoorRandomizerOptionCategories::Dungeon;
            case "enemizer":
                return DoorRandomizerOptionCategories::Enemizer;
            case "entrando":
                return DoorRandomizerOptionCategories::Entrando;
            case "gameoptions":
                return DoorRandomizerOptionCategories::GameOptions;
            case "generation":
                return DoorRandomizerOptionCategories::Generation;
            case "item":
                return DoorRandomizerOptionCategories::Item;
            case "multiworld":
                return DoorRandomizerOptionCategories::Multiworld;
            case "overworld":
                return DoorRandomizerOptionCategories::Overworld;
        }
    }
}

class DoorRandomizerOption {
    public string           $Name;
    public ?string          $Description;
    public string           $Type;
    public ?int             $Category;
    public ?int             $Subcategory;
    public ?string          $Alignment;

    public                  $DefaultValue;
    public                  $Value;

    public function __construct($Name,$Description) {
        $this->Name = $Name;
        $this->Alignment = "top";
        
        if(is_array($Description))
            $this->Description = implode("\n",$Description);
        else
            $this->Description  = $Description;
    }

    public function asCliArg() {
        return "--{$this->Name} {$this->Value}";
    }

    public function asCustomizerOption() {
        $html = "";
        switch($this->Type) {
            case "bool":
                $html = '<div class="input-group mb-3">
                <span class="input-group-text" id="label-'.$this->Type.$this->Name.'">'.ucfirst($this->Name).'</span>
                <select class="form-select" aria-label="'.$this->Type.$this->Name.'" name="'.$this->Type.$this->Name.'" aria-describedby="label-'.$this->Type.$this->Name.'">
                <option value=0>No</option>
                <option value=1>Yes</option>
                </select>
                <div data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="bottom" data-bs-title="<small>'.str_replace(array("\n","\""),array("<br />","'"),$this->Description).'</small>"><small>?</small></div></div>';
              break;
            case "string":
                $html = '<div class="input-group mb-3">
                <span class="input-group-text" id="label-'.$this->Type.$this->Name.'">'.ucfirst($this->Name).'</span>
                <input type="text" class="form-control" id="'.$this->Type.$this->Name.'" placeholder="'.$this->DefaultValue.'" aria-describedby="label-'.$this->Type.$this->Name.'">
                <div data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="bottom" data-bs-title="<small>'.str_replace(array("\n","\""),array("<br />","'"),$this->Description).'</small>"><small>?</small></div></div>';
              break;
            default:
                
        }
        return $html;
    }
}

class DoorRandomizerOptionMultiChoice extends DoorRandomizerOption {
    public $Choices;

    public function __construct($Name,$Description) {
        $this->Choices = Array();
        parent::__construct($Name,$Description);
    }

    public function asCustomizerOption() {
        $html = "";
        switch($this->Type) {
            case "multi":
                $html = '<div class="input-group mb-3"><span class="input-group-text" id="label-'.$this->Type.$this->Name.'">'.ucfirst($this->Name).'</span><select class="form-select" aria-label="'.$this->Type.$this->Name.'" name="'.$this->Type.$this->Name.'" aria-describedby="label-'.$this->Type.$this->Name.'">';
                foreach($this->Choices as $ChoiceId=>$ChoiceValue)
                    $html .= '<option value="'.$ChoiceId.'">'.$ChoiceValue.'</option>';
                $html .= '</select><div data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="bottom" data-bs-title="<small>'.str_replace(array("\n","\""),array("<br />","'"),$this->Description).'</small>"><small>?</small></div></div>';
                break;
            default:
                
        }
        return $html;
    }
}