package main

import (
	"encoding/json"
	"fmt"
	"log"
	"net/http"
	"os"
	"time"
)

type Config struct {
	AppName     string `json:"app_name"`
	Environment string `json:"environment"`
	Version     string `json:"version"`
	Hostname    string `json:"hostname"`
	Timestamp   string `json:"timestamp"`
}

type HealthStatus struct {
	Status    string `json:"status"`
	Timestamp string `json:"timestamp"`
}

func main() {
        fmt.Println("Service starting...") // added this line

	appName := os.Getenv("APP_NAME")
	if appName == "" {
		appName = "Go Microservice"
	}

	environment := os.Getenv("ENVIRONMENT")
	if environment == "" {
		environment = "development"
	}

	version := os.Getenv("VERSION")
	if version == "" {
		version = "1.0.0"
	}

	port := os.Getenv("PORT")
	if port == "" {
		port = "8080"
	}

	http.HandleFunc("/", func(w http.ResponseWriter, r *http.Request) {
		hostname, _ := os.Hostname()
		config := Config{
			AppName:     appName,
			Environment: environment,
			Version:     version,
			Hostname:    hostname,
			Timestamp:   time.Now().Format(time.RFC3339),
		}

		w.Header().Set("Content-Type", "application/json")
		json.NewEncoder(w).Encode(config)
	})

	http.HandleFunc("/health", func(w http.ResponseWriter, r *http.Request) {
		status := HealthStatus{
			Status:    "healthy",
			Timestamp: time.Now().Format(time.RFC3339),
		}

		w.Header().Set("Content-Type", "application/json")
		json.NewEncoder(w).Encode(status)
	})

	log.Printf("Starting server on port %s", port)
	log.Fatal(http.ListenAndServe(":"+port, nil))
}
