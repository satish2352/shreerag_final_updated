Owner Department 

Username- owner@gmail.com 

Password- FfAtgNQorb 

 

 

 

use Illuminate\Support\Facades\DB; use Illuminate\Support\Facades\Log; 

public function boot() { DB::listen(function ($query) { Log::info("Query Time: {$query->time} ms; SQL: {$query->sql}; Bindings: " . json_encode($query->bindings)); }); } 

CREATE TABLE DIV_PP02_Digital ( id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, TriggerTime DATETIME(3) NOT NULL, cola_IoT_NumberCode INT DEFAULT NULL, colt_ShiftSelected VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL, cola_Actual_Strokes INT DEFAULT NULL, cold_RunTime BIT(1) DEFAULT NULL, cold_IdleTime BIT(1) DEFAULT NULL, cold_Loading_UnloadingTime BIT(1) DEFAULT NULL, cold_Total_DownTime BIT(1) DEFAULT NULL, cold_Unclassified_Downtime BIT(1) DEFAULT NULL, cola_Loss_NumberCode INT DEFAULT NULL, cola_OperationNumber INT DEFAULT NULL, PRIMARY KEY (id) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; 

DELIMITER $$

CREATE PROCEDURE CopyDataInBatches() 
BEGIN
    DECLARE batchSize INT DEFAULT 70000; 
    DECLARE offsetVal INT DEFAULT 0; 
    DECLARE totalRows INT; 

    -- Get total rows from source table 
    SELECT COUNT(*) INTO totalRows 
    FROM DIV_PP02_Digital; 

    -- Loop to insert data in batches 
    WHILE offsetVal < totalRows DO
        INSERT INTO div_pp06_digital (
            TriggerTime, 
            cola_IoT_NumberCode, 
            colt_ShiftSelected, 
            cola_Actual_Strokes, 
            cold_RunTime, 
            cold_IdleTime, 
            cold_Loading_UnloadingTime, 
            cold_Total_DownTime, 
            cold_Unclassified_Downtime, 
            cola_Loss_NumberCode, 
            cola_OperationNumber
        )
        SELECT 
            TriggerTime, 
            cola_IoT_NumberCode, 
            colt_ShiftSelected, 
            cola_Actual_Strokes, 
            cold_RunTime, 
            cold_IdleTime, 
            cold_Loading_UnloadingTime, 
            cold_Total_DownTime, 
            cold_Unclassified_Downtime, 
            cola_Loss_NumberCode, 
            cola_OperationNumber 
        FROM DIV_PP02_Digital 
        LIMIT batchSize OFFSET offsetVal;

        -- Update the offset for the next batch
        SET offsetVal = offsetVal + batchSize;
    END WHILE; 
END$$

DELIMITER ;


CALL CopyDataInBatches(); 

 

DROP PROCEDURE IF EXISTS CopyDataInBatches; 

 

 


CREATE INDEX idx_numbercode_triggertime ON div_pp06_digital (cola_IoT_NumberCode, TriggerTime); 

CREATE INDEX idx_triggertime_actualstrokes ON div_pp06_digital (TriggerTime, cola_Actual_Strokes); 

CREATE INDEX idx_numbercode_actualstrokes ON div_pp06_digital (cola_IoT_NumberCode, cola_Actual_Strokes); 
CREATE INDEX idx_iot_time ON div_pp06_digital (cola_IoT_NumberCode, TriggerTime); 
CREATE INDEX idx_loss_strokes ON div_pp06_digital (cola_IoT_NumberCode, TriggerTime) WHERE cola_Actual_Strokes > 0; 

CREATE INDEX idx_trigger_time ON div_pp06_digital (TriggerTime); CREATE INDEX idx_iot_strokes ON div_pp06_digital (cola_IoT_NumberCode, cola_Actual_Strokes, TriggerTime); 


CREATE INDEX idx_div_iot_loss_time_strokes ON div_pp06_digital (cola_IoT_NumberCode, cola_Loss_NumberCode, TriggerTime, cola_Actual_Strokes); 

 CREATE INDEX idx_iot_time_strokes ON div_pp06_digital (cola_IoT_NumberCode, TriggerTime, cola_Actual_Strokes);


CREATE INDEX idx_code_time ON div_pp06_digital (cola_IoT_NumberCode, TriggerTime);


CREATE INDEX idx_iot_time_loss_strokes ON div_pp06_digital (cola_IoT_NumberCode, TriggerTime, cola_Loss_NumberCode, cola_Actual_Strokes);


CREATE INDEX idx_loss_strokes ON div_pp06_digital (cola_Loss_NumberCode, cola_Actual_Strokes);

 

 

 