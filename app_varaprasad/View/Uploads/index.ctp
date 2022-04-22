<div class="outer-container">
    <?= $this->Form->create('Upload', ['type' => 'file']) ?>
    
        <div>
            <label>Choose Excel File</label>
            <input type="file" name="data[Upload][fileinfo]" id="file" accept=".xls,.xlsx,.csv">
            <button type="submit" class="btn-submit">Import</button>
        </div>
    
    <?= $this->Form->end() ?>
</div>